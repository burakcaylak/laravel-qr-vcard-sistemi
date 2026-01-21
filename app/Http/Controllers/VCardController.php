<?php

namespace App\Http\Controllers;

use App\Http\Requests\VCardRequest;
use App\Models\VCard;
use App\Models\Category;
use App\Models\VCardTemplate;
use App\Helpers\ActivityLogHelper;
use App\Helpers\CacheHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class VCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\App\DataTables\VCardsDataTable $dataTable)
    {
        return $dataTable->render('pages.v-card.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = CacheHelper::getActiveCategories();

        $templates = VCardTemplate::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.v-card.create', compact('categories', 'templates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VCardRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        // is_active checkbox işaretli değilse form'dan gönderilmez, bu yüzden manuel olarak false yap
        if (!$request->has('is_active')) {
            $data['is_active'] = false;
        } else {
            $data['is_active'] = (bool) $request->input('is_active');
        }

        // Görsel yükleme
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('v-cards/images', $imageName, 'public');
            $data['image_path'] = $imagePath;
        }

        // Token model'de otomatik oluşturulacak
        $vCard = VCard::create($data);

        // vCard'ı aktif olarak işaretle
        $vCard->is_active = true;
        $vCard->save();

        // QR kod görselini oluştur (otomatik aktif)
        // Not: VCard için ayrı bir job gerekebilir, şimdilik direkt oluştur
        $this->generateQrImage($vCard);

        // Structured logging
        \Log::info('vCard created', [
            'user_id' => auth()->id(),
            'v_card_id' => $vCard->id,
            'name' => $vCard->getLocalizedField('name'),
            'token' => $vCard->token,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        ActivityLogHelper::logVCard('created', $vCard);

        return redirect()->route('v-card.show', $vCard)
            ->with('success', __('common.v_card_created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(VCard $vCard)
    {
        $vCard->load(['user', 'category']);

        // Aktivite kayıtlarını çek
        $activityLogs = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('activity_logs')) {
            $activityLogs = \Illuminate\Support\Facades\DB::table('activity_logs')
                ->leftJoin('users', function ($join) {
                    $join->on('activity_logs.causer_id', '=', 'users.id')
                        ->where('activity_logs.causer_type', '=', 'App\Models\User');
                })
                ->where('activity_logs.subject_type', 'App\Models\VCard')
                ->where('activity_logs.subject_id', $vCard->id)
                ->select(
                    'activity_logs.id',
                    'activity_logs.description',
                    'activity_logs.event',
                    'activity_logs.properties',
                    'activity_logs.created_at',
                    'users.name as user_name',
                    'users.email as user_email'
                )
                ->orderBy('activity_logs.created_at', 'desc')
                ->get()
                ->map(function ($log) {
                    if (is_string($log->properties)) {
                        $log->properties = json_decode($log->properties, true);
                    }
                    return $log;
                });
        }

        return view('pages.v-card.show', compact('vCard', 'activityLogs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VCard $vCard)
    {
        $categories = CacheHelper::getActiveCategories();

        $templates = VCardTemplate::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.v-card.edit', compact('vCard', 'categories', 'templates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VCardRequest $request, VCard $vCard)
    {
        $data = $request->validated();

        // is_active checkbox işaretli değilse form'dan gönderilmez, bu yüzden manuel olarak false yap
        if (!$request->has('is_active')) {
            $data['is_active'] = false;
        } else {
            $data['is_active'] = (bool) $request->input('is_active');
        }

        // Görsel yükleme
        if ($request->hasFile('image')) {
            // Eski görseli sil
            if ($vCard->image_path && Storage::disk('public')->exists($vCard->image_path)) {
                Storage::disk('public')->delete($vCard->image_path);
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('v-cards/images', $imageName, 'public');
            $data['image_path'] = $imagePath;
        }

        $vCard->update($data);

        // QR kod görselini yeniden oluştur
        $this->generateQrImage($vCard);

        ActivityLogHelper::logVCard('updated', $vCard);

        return redirect()->route('v-card.show', $vCard)
            ->with('success', __('common.v_card_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VCard $vCard)
    {
        ActivityLogHelper::logVCard('deleted', $vCard);

        if ($vCard->file_path && Storage::disk('public')->exists($vCard->file_path)) {
            Storage::disk('public')->delete($vCard->file_path);
        }

        $vCard->delete();

        return redirect()->route('v-card.index')
            ->with('success', __('common.v_card_deleted'));
    }

    /**
     * Download QR code image.
     */
    public function download(VCard $vCard)
    {
        $vCard->increment('scan_count');

        ActivityLogHelper::logVCard('downloaded', $vCard);

        if (!$vCard->file_path || !Storage::disk('public')->exists($vCard->file_path)) {
            $this->generateQrImage($vCard);
            $vCard->refresh();
        }

        if ($vCard->file_path && Storage::disk('public')->exists($vCard->file_path)) {
            return Storage::disk('public')->download(
                $vCard->file_path,
                'vcard-' . $vCard->token . '.svg'
            );
        }

        abort(404, 'QR kod görseli bulunamadı.');
    }

    /**
     * Public access to vCard via token (herkese açık)
     */
    public function access($token)
    {
        $vCard = VCard::with('template')
            ->where('token', $token)
            ->where('is_active', true)
            ->first();
        
        if (!$vCard) {
            abort(404, 'vCard bulunamadı veya aktif değil.');
        }
        
        // Ekstra kontrol (güvenlik için)
        if (!$vCard->is_active) {
            abort(403, 'vCard aktif değil.');
        }
        
        if ($vCard->is_expired) {
            abort(410, 'vCard\'ın süresi dolmuş.');
        }
        
        // Scan sayısını artır
        $vCard->increment('scan_count');
        
        // vCard içeriğini oluştur
        $vCardContent = $this->generateVCardContent($vCard);
        
        // vCard görselini göster
        return view('pages.v-card.access', [
            'vCard' => $vCard,
            'vCardContent' => $vCardContent,
        ]);
    }

    /**
     * Generate QR code image for vCard.
     */
    public function generateQrImage(VCard $vCard)
    {
        // QR kod içeriği olarak vCard linkini kullan
        $vCardUrl = route('vcard.access', $vCard->token);
        
        $size = 300;
        $format = 'svg';
        $fileName = 'v-cards/' . $vCard->token . '.' . $format;

        try {
            $qrImage = QrCodeGenerator::size($size)
                ->format('svg')
                ->generate($vCardUrl);

            Storage::disk('public')->put($fileName, $qrImage);

            $vCard->file_path = $fileName;
            $vCard->save();
        } catch (\Exception $e) {
            \Log::error('Failed to generate QR code for vCard', [
                'v_card_id' => $vCard->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Generate vCard content in vCard format (vCard 3.0)
     * UTF-8 karakterler için Quoted-Printable encoding kullanılır
     */
    protected function generateVCardContent(VCard $vCard): string
    {
        $locale = app()->getLocale();
        
        $name = $vCard->getLocalizedField('name', $locale) ?? '';
        $title = $vCard->getLocalizedField('title', $locale) ?? '';
        $company = $vCard->getLocalizedField('company', $locale) ?? '';
        $address = $vCard->getLocalizedField('address', $locale) ?? '';
        $phone = $vCard->phone ?? $vCard->getLocalizedField('phone', $locale) ?? '';
        $mobilePhone = $vCard->mobile_phone ?? $vCard->getLocalizedField('mobile_phone', $locale) ?? '';
        $companyPhone = $vCard->getLocalizedField('company_phone', $locale) ?? '';
        $extension = $vCard->getLocalizedField('extension', $locale) ?? '';
        $fax = $vCard->getLocalizedField('fax', $locale) ?? '';
        $email = $vCard->email ?? $vCard->getLocalizedField('email', $locale) ?? '';
        $website = $vCard->website ?? $vCard->getLocalizedField('website', $locale) ?? '';

        // UTF-8 BOM ekle (VCF dosyasının UTF-8 olduğunu belirtmek için)
        $vCardLines = [
            "\xEF\xBB\xBFBEGIN:VCARD", // UTF-8 BOM + BEGIN:VCARD
            'VERSION:3.0',
        ];

        // Name
        if ($name) {
            $nameParts = explode(' ', $name, 2);
            $vCardLines[] = 'FN;CHARSET=UTF-8;ENCODING=QUOTED-PRINTABLE:' . $this->quotedPrintableEncode($name);
            $vCardLines[] = 'N;CHARSET=UTF-8;ENCODING=QUOTED-PRINTABLE:' . 
                $this->quotedPrintableEncode($nameParts[1] ?? '') . ';' . 
                $this->quotedPrintableEncode($nameParts[0] ?? '') . ';;;';
        }

        // Title
        if ($title) {
            $vCardLines[] = 'TITLE;CHARSET=UTF-8;ENCODING=QUOTED-PRINTABLE:' . $this->quotedPrintableEncode($title);
        }

        // Organization
        if ($company) {
            $vCardLines[] = 'ORG;CHARSET=UTF-8;ENCODING=QUOTED-PRINTABLE:' . $this->quotedPrintableEncode($company);
        }

        // Address
        if ($address) {
            $addressFormatted = str_replace(["\n", "\r"], ';', $address);
            $vCardLines[] = 'ADR;TYPE=WORK;CHARSET=UTF-8;ENCODING=QUOTED-PRINTABLE:;;' . 
                $this->quotedPrintableEncode($addressFormatted) . ';;;;';
        }

        // Phone numbers (phone numbers don't need encoding as they are ASCII)
        if ($phone) {
            $vCardLines[] = 'TEL;TYPE=WORK:' . $phone;
        }
        
        if ($companyPhone) {
            $vCardLines[] = 'TEL;TYPE=WORK:' . $companyPhone;
        }
        
        if ($extension && $companyPhone) {
            $vCardLines[] = 'TEL;TYPE=WORK,EXT:' . $companyPhone . ';EXT=' . $extension;
        }
        
        if ($mobilePhone) {
            $vCardLines[] = 'TEL;TYPE=CELL:' . $mobilePhone;
        }
        
        if ($fax) {
            $vCardLines[] = 'TEL;TYPE=FAX:' . $fax;
        }

        // Email (email addresses are ASCII, no encoding needed)
        if ($email) {
            $vCardLines[] = 'EMAIL;TYPE=WORK:' . $email;
        }

        // Website (URLs are ASCII, no encoding needed)
        if ($website) {
            $vCardLines[] = 'URL:' . $website;
        }

        $vCardLines[] = 'END:VCARD';

        $vcfContent = implode("\r\n", $vCardLines);
        
        // UTF-8 encoding'i garanti et
        if (!mb_check_encoding($vcfContent, 'UTF-8')) {
            $vcfContent = mb_convert_encoding($vcfContent, 'UTF-8', 'UTF-8');
        }
        
        return $vcfContent;
    }

    /**
     * Encode string to Quoted-Printable format for VCF
     * Türkçe karakterler (İ, Ç, Ş, Ğ, Ü, Ö) için özel encoding
     */
    protected function quotedPrintableEncode(string $string): string
    {
        if (empty($string)) {
            return '';
        }
        
        // UTF-8 string'i byte array'e çevir
        $bytes = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
        $encoded = '';
        $lineLength = 0;
        $maxLineLength = 75; // VCF formatı maksimum satır uzunluğu
        
        for ($i = 0; $i < strlen($bytes); $i++) {
            $byte = ord($bytes[$i]);
            
            // ASCII karakterler (32-126) ve bazı özel karakterler doğrudan yazılır
            // VCF formatında =, ;, :, \ karakterleri escape edilmelidir
            if ($byte >= 32 && $byte <= 126 && !in_array(chr($byte), ['=', ';', ':', '\\'])) {
                // Satır uzunluğunu kontrol et
                if ($lineLength >= $maxLineLength - 1) {
                    $encoded .= "=\r\n ";
                    $lineLength = 1;
                }
                $encoded .= chr($byte);
                $lineLength++;
            } else {
                // UTF-8 byte'larını hex formatında encode et (=XX)
                $hex = strtoupper(dechex($byte));
                if (strlen($hex) == 1) {
                    $hex = '0' . $hex;
                }
                
                // Satır uzunluğunu kontrol et (=XX 3 karakter)
                if ($lineLength >= $maxLineLength - 3) {
                    $encoded .= "=\r\n ";
                    $lineLength = 1;
                }
                
                $encoded .= '=' . $hex;
                $lineLength += 3;
            }
        }
        
        return $encoded;
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'ids' => 'required|array',
            'ids.*' => 'exists:v_cards,id',
        ]);

        $ids = $request->ids;
        $action = $request->action;

        \DB::beginTransaction();
        try {
            switch ($action) {
                case 'delete':
                    VCard::whereIn('id', $ids)
                        ->where('user_id', auth()->id())
                        ->delete();
                    break;
                case 'activate':
                    VCard::whereIn('id', $ids)
                        ->where('user_id', auth()->id())
                        ->update(['is_active' => true]);
                    break;
                case 'deactivate':
                    VCard::whereIn('id', $ids)
                        ->where('user_id', auth()->id())
                        ->update(['is_active' => false]);
                    break;
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('common.bulk_action_success'),
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => __('common.bulk_action_error'),
            ], 500);
        }
    }
}
