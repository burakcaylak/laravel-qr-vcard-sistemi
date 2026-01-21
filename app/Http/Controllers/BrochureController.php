<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrochureRequest;
use App\Models\Brochure;
use App\Models\Category;
use App\Models\File;
use App\Helpers\ActivityLogHelper;
use App\Helpers\CacheHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class BrochureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\App\DataTables\BrochuresDataTable $dataTable)
    {
        return $dataTable->render('pages.brochure.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = CacheHelper::getActiveCategories();

        $files = File::where(function($query) {
                $query->where('type', 'pdf')
                      ->orWhere('mime_type', 'application/pdf');
            })
            ->latest()
            ->get();

        return view('pages.brochure.create', compact('categories', 'files'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrochureRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        // is_active checkbox işaretli değilse form'dan gönderilmez
        if (!$request->has('is_active')) {
            $data['is_active'] = false;
        } else {
            $data['is_active'] = (bool) $request->input('is_active');
        }

        // PDF dosyası yükleme
        if ($request->hasFile('pdf_file')) {
            $pdfFile = $request->file('pdf_file');
            $pdfName = time() . '_' . $pdfFile->getClientOriginalName();
            $pdfPath = $pdfFile->storeAs('brochures/pdfs', $pdfName, 'public');
            $data['pdf_path'] = $pdfPath;
        } elseif ($request->has('file_id')) {
            // Mevcut dosyadan kullan
            $file = File::findOrFail($request->input('file_id'));
            $data['pdf_path'] = $file->path;
            $data['file_id'] = $file->id;
        }

        // Arkaplan görseli yükleme ve background_type belirleme
        if ($request->hasFile('background_image')) {
            $image = $request->file('background_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('brochures/backgrounds', $imageName, 'public');
            $data['background_image_path'] = $imagePath;
            $data['background_type'] = 'image';
            // Görsel seçildiğinde bile background_color default değerini koru (nullable değil)
            if (empty($data['background_color'])) {
                $data['background_color'] = '#ffffff';
            }
        } elseif ($request->has('background_image_file_id')) {
            // Media Library'den seçilen görsel
            $bgFile = File::find($request->input('background_image_file_id'));
            if ($bgFile && $bgFile->type === 'image') {
                $data['background_image_path'] = $bgFile->path;
                $data['background_type'] = 'image';
                // Görsel seçildiğinde bile background_color default değerini koru (nullable değil)
                if (empty($data['background_color'])) {
                    $data['background_color'] = '#ffffff';
                }
            } else {
                // Media Library'den geçersiz dosya seçilmişse veya yoksa renk kullan
                $data['background_type'] = 'color';
                $data['background_image_path'] = null;
                if (empty($data['background_color'])) {
                    $data['background_color'] = '#ffffff';
                }
            }
        } else {
            // Görsel yoksa renk kullan
            $data['background_type'] = 'color';
            $data['background_image_path'] = null;
            // Varsayılan arkaplan rengi
            if (empty($data['background_color'])) {
                $data['background_color'] = '#ffffff';
            }
        }

        // Password protection kontrolü
        if ($request->has('password_protected') && $request->input('password_protected')) {
            $data['password_protected'] = true;
            // Şifre varsa hash'le
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                // Şifre korumalı ama şifre girilmemiş
                return back()->withErrors(['password' => __('common.password_required_when_protected')]);
            }
        } else {
            // Password protection kapalı
            $data['password_protected'] = false;
            $data['password'] = null;
        }

        $brochure = Brochure::create($data);

        // QR kod görselini oluştur
        $this->generateQrImage($brochure);

        ActivityLogHelper::logBrochure('created', $brochure);

        return redirect()->route('brochure.show', $brochure)
            ->with('success', __('common.brochure_created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Brochure $brochure)
    {
        $brochure->load(['user', 'category', 'file']);

        return view('pages.brochure.show', compact('brochure'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brochure $brochure)
    {
        $categories = CacheHelper::getActiveCategories();

        $files = File::where(function($query) {
                $query->where('type', 'pdf')
                      ->orWhere('mime_type', 'application/pdf');
            })
            ->latest()
            ->get();

        return view('pages.brochure.edit', compact('brochure', 'categories', 'files'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrochureRequest $request, Brochure $brochure)
    {
        $data = $request->validated();

        // is_active checkbox işaretli değilse form'dan gönderilmez
        if (!$request->has('is_active')) {
            $data['is_active'] = false;
        } else {
            $data['is_active'] = (bool) $request->input('is_active');
        }

        // PDF dosyası yükleme
        if ($request->hasFile('pdf_file')) {
            // Eski PDF'i sil
            if ($brochure->pdf_path && Storage::disk('public')->exists($brochure->pdf_path)) {
                Storage::disk('public')->delete($brochure->pdf_path);
            }
            
            $pdfFile = $request->file('pdf_file');
            $pdfName = time() . '_' . $pdfFile->getClientOriginalName();
            $pdfPath = $pdfFile->storeAs('brochures/pdfs', $pdfName, 'public');
            $data['pdf_path'] = $pdfPath;
        } elseif ($request->has('file_id')) {
            $file = File::findOrFail($request->input('file_id'));
            $data['pdf_path'] = $file->path;
            $data['file_id'] = $file->id;
        }

        // Arkaplan görseli yükleme ve background_type belirleme
        if ($request->hasFile('background_image')) {
            // Eski görseli sil (sadece direkt yüklenen görselleri sil, Media Library'den gelenleri silme)
            if ($brochure->background_image_path && Storage::disk('public')->exists($brochure->background_image_path)) {
                // Sadece brochures/backgrounds klasöründeki dosyaları sil
                if (strpos($brochure->background_image_path, 'brochures/backgrounds') !== false) {
                    Storage::disk('public')->delete($brochure->background_image_path);
                }
            }
            
            $image = $request->file('background_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('brochures/backgrounds', $imageName, 'public');
            $data['background_image_path'] = $imagePath;
            $data['background_type'] = 'image';
            // Görsel seçildiğinde bile background_color default değerini koru (nullable değil)
            if (empty($data['background_color'])) {
                $data['background_color'] = $brochure->background_color ?? '#ffffff';
            }
        } elseif ($request->has('background_image_file_id')) {
            // Media Library'den seçilen görsel
            $bgFile = File::find($request->input('background_image_file_id'));
            if ($bgFile && $bgFile->type === 'image') {
                // Eski görseli sil (sadece direkt yüklenen görselleri sil)
                if ($brochure->background_image_path && Storage::disk('public')->exists($brochure->background_image_path)) {
                    if (strpos($brochure->background_image_path, 'brochures/backgrounds') !== false) {
                        Storage::disk('public')->delete($brochure->background_image_path);
                    }
                }
                $data['background_image_path'] = $bgFile->path;
                $data['background_type'] = 'image';
                // Görsel seçildiğinde bile background_color default değerini koru (nullable değil)
                if (empty($data['background_color'])) {
                    $data['background_color'] = $brochure->background_color ?? '#ffffff';
                }
            } else {
                // Media Library'den geçersiz dosya seçilmişse veya yoksa renk kullan
                $data['background_type'] = 'color';
                $data['background_image_path'] = null;
                if (empty($data['background_color'])) {
                    $data['background_color'] = $brochure->background_color ?? '#ffffff';
                }
            }
        } else {
            // Görsel yoksa renk kullan
            $data['background_type'] = 'color';
            $data['background_image_path'] = null;
            // Varsayılan arkaplan rengi
            if (empty($data['background_color'])) {
                $data['background_color'] = $brochure->background_color ?? '#ffffff';
            }
        }

        // background_type belirlenmemişse varsayılan değer
        if (!isset($data['background_type'])) {
            if (!empty($data['background_image_path'])) {
                $data['background_type'] = 'image';
            } else {
                $data['background_type'] = 'color';
            }
        }
        
        // Arkaplan tipi değiştiyse, diğer tipin dosyasını sil
        if (isset($data['background_type']) && $data['background_type'] === 'color' && $brochure->background_image_path) {
            if (Storage::disk('public')->exists($brochure->background_image_path)) {
                // Sadece brochures/backgrounds klasöründeki dosyaları sil
                if (strpos($brochure->background_image_path, 'brochures/backgrounds') !== false) {
                    Storage::disk('public')->delete($brochure->background_image_path);
                }
            }
            $data['background_image_path'] = null;
        }
        
        // background_color her zaman bir değere sahip olmalı (nullable değil)
        if (empty($data['background_color'])) {
            $data['background_color'] = $brochure->background_color ?? '#ffffff';
        }

        // Password protection kontrolü
        // Checkbox gönderilmediğinde false olarak kabul et (Laravel checkbox göndermezse)
        $passwordProtected = $request->has('password_protected') && $request->input('password_protected');
        
        if ($passwordProtected) {
            $data['password_protected'] = true;
            // Şifre varsa hash'le
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } elseif (empty($brochure->password)) {
                // Şifre korumalı ama şifre girilmemiş ve mevcut şifre yoksa hata
                return back()->withErrors(['password' => __('common.password_required_when_protected')]);
            } else {
                // Şifre değiştirilmemiş, mevcut şifreyi koru
                unset($data['password']);
            }
        } else {
            // Password protection kapalı
            $data['password_protected'] = false;
            // Şifre koruması kapatılıyorsa şifreyi ve session'ı temizle
            if ($brochure->password_protected) {
                $data['password'] = null;
                // Session'ı temizle
                $sessionKey = 'brochure_' . $brochure->token . '_verified';
                session()->forget($sessionKey);
            } else {
                // Şifre koruması zaten kapalıydı, şifre alanını değiştirme
                unset($data['password']);
            }
        }

        $brochure->update($data);
        
        // Model'i yeniden yükle
        $brochure->refresh();

        // QR kod görselini yeniden oluştur
        $this->generateQrImage($brochure);

        ActivityLogHelper::logBrochure('updated', $brochure);

        return redirect()->route('brochure.show', $brochure)
            ->with('success', __('common.brochure_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brochure $brochure)
    {
        ActivityLogHelper::logBrochure('deleted', $brochure);

        // Dosyaları sil
        if ($brochure->pdf_path && Storage::disk('public')->exists($brochure->pdf_path)) {
            Storage::disk('public')->delete($brochure->pdf_path);
        }

        if ($brochure->background_image_path && Storage::disk('public')->exists($brochure->background_image_path)) {
            Storage::disk('public')->delete($brochure->background_image_path);
        }

        if ($brochure->qr_code_path && Storage::disk('public')->exists($brochure->qr_code_path)) {
            Storage::disk('public')->delete($brochure->qr_code_path);
        }

        $brochure->delete();

        return redirect()->route('brochure.index')
            ->with('success', __('common.brochure_deleted'));
    }

    /**
     * Download QR code image.
     */
    public function download(Brochure $brochure)
    {
        ActivityLogHelper::logBrochure('downloaded', $brochure);

        if (!$brochure->qr_code_path || !Storage::disk('public')->exists($brochure->qr_code_path)) {
            $this->generateQrImage($brochure);
            $brochure->refresh();
        }

        if ($brochure->qr_code_path && Storage::disk('public')->exists($brochure->qr_code_path)) {
            return Storage::disk('public')->download(
                $brochure->qr_code_path,
                'brochure-' . $brochure->token . '.svg'
            );
        }

        abort(404, 'QR kod görseli bulunamadı.');
    }

    /**
     * Public access to brochure via token (herkese açık - flipbook görüntüleme)
     */
    public function access($token)
    {
        $brochure = Brochure::where('token', $token)
            ->where('is_active', true)
            ->first();
        
        if (!$brochure) {
            abort(404, 'Kitapçık bulunamadı veya aktif değil.');
        }
        
        // Model'i veritabanından yeniden yükle (cache sorununu önlemek için)
        $brochure->refresh();
        
        if ($brochure->is_expired) {
            abort(410, 'Kitapçığın süresi dolmuş.');
        }

        // Şifre kontrolü
        if ($brochure->password_protected) {
            $sessionKey = 'brochure_' . $token . '_verified';
            if (!session()->has($sessionKey)) {
                return redirect()->route('brochure.password', $token);
            }
        }
        
        // View sayısını artır
        $brochure->increment('view_count');
        
        // PDF dosyasının route URL'ini al (CORS header'ları ile)
        $pdfUrl = route('brochure.pdf', $brochure->token);
        
        return view('pages.brochure.access', [
            'brochure' => $brochure,
            'pdfUrl' => $pdfUrl,
        ]);
    }

    /**
     * Serve PDF file with CORS headers.
     */
    public function pdf($token)
    {
        // OPTIONS isteği için CORS header'larını döndür
        if (request()->isMethod('options')) {
            return response('', 200, [
                'Access-Control-Allow-Origin' => request()->header('Origin', '*'),
                'Access-Control-Allow-Methods' => 'GET, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, X-Requested-With, Accept, Authorization',
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Max-Age' => '86400',
            ]);
        }
        
        $brochure = Brochure::where('token', $token)
            ->where('is_active', true)
            ->first();
        
        if (!$brochure) {
            abort(404, 'Kitapçık bulunamadı veya aktif değil.');
        }
        
        if ($brochure->is_expired) {
            abort(410, 'Kitapçığın süresi dolmuş.');
        }
        
        if (!Storage::disk('public')->exists($brochure->pdf_path)) {
            abort(404, 'PDF dosyası bulunamadı.');
        }
        
        $filePath = Storage::disk('public')->path($brochure->pdf_path);
        
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Access-Control-Allow-Origin' => request()->header('Origin', '*'),
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, X-Requested-With, Accept, Authorization',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Expose-Headers' => 'Content-Length, Content-Type',
        ]);
    }

    /**
     * Generate QR code image for brochure.
     */
    protected function generateQrImage(Brochure $brochure)
    {
        // QR kod içeriği olarak brochure linkini kullan
        $brochureUrl = route('brochure.access', $brochure->token);
        
        $size = 300;
        $format = 'svg';
        $fileName = 'brochures/qr-codes/' . $brochure->token . '.' . $format;

        try {
            $qrImage = QrCodeGenerator::size($size)
                ->format('svg')
                ->generate($brochureUrl);

            Storage::disk('public')->put($fileName, $qrImage);

            $brochure->qr_code_path = $fileName;
            $brochure->saveQuietly();
        } catch (\Exception $e) {
            \Log::error('Failed to generate QR code for brochure', [
                'brochure_id' => $brochure->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function password($token)
    {
        $brochure = Brochure::where('token', $token)
            ->where('is_active', true)
            ->first();

        if (!$brochure) {
            abort(404, 'Kitapçık bulunamadı veya aktif değil.');
        }

        if (!$brochure->password_protected) {
            return redirect()->route('brochure.access', $token);
        }

        return view('pages.brochure.password', compact('brochure'));
    }

    public function verifyPassword(Request $request, $token)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $brochure = Brochure::where('token', $token)
            ->where('is_active', true)
            ->first();

        if (!$brochure) {
            abort(404, 'Kitapçık bulunamadı veya aktif değil.');
        }

        if ($brochure->verifyPassword($request->password)) {
            session(['brochure_' . $token . '_verified' => true]);
            return redirect()->route('brochure.access', $token);
        }

        return back()->withErrors(['password' => __('common.invalid_password')]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'ids' => 'required|array',
            'ids.*' => 'exists:brochures,id',
        ]);

        $ids = $request->ids;
        $action = $request->action;

        \DB::beginTransaction();
        try {
            switch ($action) {
                case 'delete':
                    Brochure::whereIn('id', $ids)
                        ->where('user_id', auth()->id())
                        ->delete();
                    break;
                case 'activate':
                    Brochure::whereIn('id', $ids)
                        ->where('user_id', auth()->id())
                        ->update(['is_active' => true]);
                    break;
                case 'deactivate':
                    Brochure::whereIn('id', $ids)
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
