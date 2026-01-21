<?php

namespace App\Http\Controllers;

use App\Http\Requests\QrCodeRequest;
use App\Models\File;
use App\Models\QrCode;
use App\Helpers\ActivityLogHelper;
use App\Helpers\CacheHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class QrCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\App\DataTables\QrCodesDataTable $dataTable)
    {
        return $dataTable->render('pages.qr-code.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $files = File::latest()
            ->get(); // Tüm dosyaları göster

        $categories = CacheHelper::getActiveCategories();

        // Media Library'den dosyaları al (File modelindeki dosyalar)
        $files = \App\Models\File::orderBy('created_at', 'desc')->get();

        return view('pages.qr-code.create', compact('files', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QrCodeRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        // is_active checkbox işaretli değilse form'dan gönderilmez, bu yüzden manuel olarak false yap
        if (!$request->has('is_active')) {
            $data['is_active'] = false;
        } else {
            $data['is_active'] = (bool) $request->input('is_active');
        }

        // file_id varsa kaydet
        if ($request->has('file_id')) {
            $data['file_id'] = $request->input('file_id');
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

        // Token model'de otomatik oluşturulacak
        // Content'i create'den sonra ayarlayacağız (token'a ihtiyacımız var)

        // Content ayarla - file ve multi_file tiplerinde otomatik oluşturulacak
        // Token'a ihtiyacımız var, bu yüzden önce token oluştur
        if ($request->qr_type === 'file' || $request->qr_type === 'multi_file') {
            // Token oluştur (model'de de oluşturulacak ama burada da oluşturuyoruz)
            if (empty($data['token'])) {
                $data['token'] = \Illuminate\Support\Str::uuid()->toString();
            }
            $data['content'] = route('qr.access', $data['token']);
        } elseif ($request->qr_type === 'url') {
            // URL tipi için content kullanıcının girdiği URL olmalı
            // Eğer content boşsa veya QR kodun kendi URL'si ise hata ver
            if (empty($data['content'])) {
                \Log::error('URL tipi QR kod için content boş', ['request' => $request->all()]);
                return back()->withErrors(['content' => __('common.url_content_required')])->withInput();
            }

            // Kullanıcının girdiği değeri olduğu gibi kaydet (otomatik http:// ekleme)
            $data['content'] = trim($data['content']);

            // QR kodun kendi erişim URL'sini engelle (sonsuz döngüyü önle)
            $qrAccessRoute = route('qr.access', 'dummy-token');
            $qrAccessPath = parse_url($qrAccessRoute, PHP_URL_PATH);
            if (strpos($data['content'], $qrAccessPath) !== false || preg_match('/\/qr\/[a-f0-9-]+/i', $data['content'])) {
                \Log::error('URL tipi QR kod için content QR kodun kendi URL\'si olamaz', ['content' => $data['content']]);
                return back()->withErrors(['content' => __('common.url_cannot_be_self')])->withInput();
            }

            \Log::info('URL tipi QR kod oluşturuluyor', ['content' => $data['content'], 'qr_type' => $request->qr_type]);
        }

        $qrCode = QrCode::create($data);

        // Çoklu dosya varsa pivot table'a kaydet (button_names ile)
        if ($request->qr_type === 'multi_file') {
            $fileIds = $request->input('file_ids', []);
            $buttonNames = $request->input('button_names', []);

            if (is_array($fileIds) && count($fileIds) > 0) {
                $syncData = [];
                $sortOrder = 0;

                // Her dosya için ayrı bir kayıt oluştur (aynı dosya birden fazla kez eklenebilir)
                foreach ($fileIds as $index => $fileId) {
                    if (!empty($fileId)) { // Boş değerleri atla
                        // Aynı dosya birden fazla kez eklenebilir, bu yüzden unique key kullan
                        $uniqueKey = $fileId . '_' . $index;
                        $syncData[$uniqueKey] = [
                            'file_id' => $fileId,
                            'sort_order' => $sortOrder,
                            'button_name' => isset($buttonNames[$index]) && !empty($buttonNames[$index])
                                ? $buttonNames[$index]
                                : 'Dosya ' . ($sortOrder + 1)
                        ];
                        $sortOrder++;
                    }
                }

                if (count($syncData) > 0) {
                    try {
                        // Önce mevcut ilişkileri temizle
                        $qrCode->files()->detach();
                        // Sonra yeni ilişkileri ekle
                        foreach ($syncData as $uniqueKey => $data) {
                            try {
                                $fileId = $data['file_id'];
                                unset($data['file_id']); // file_id'yi pivot data'dan çıkar
                                $qrCode->files()->attach($fileId, $data);
                                \Log::info('File attached on create', ['qr_code_id' => $qrCode->id, 'file_id' => $fileId, 'pivot' => $data]);
                            } catch (\Exception $e) {
                                \Log::error('Failed to attach file', [
                                    'qr_code_id' => $qrCode->id,
                                    'file_id' => $data['file_id'] ?? 'unknown',
                                    'error' => $e->getMessage()
                                ]);
                            }
                        }
                        \Log::info('Files synced on create', [
                            'count' => count($syncData),
                            'sync_data' => $syncData,
                            'qr_code_id' => $qrCode->id,
                            'actual_count' => $qrCode->files()->count()
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Failed to sync files', [
                            'qr_code_id' => $qrCode->id,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                    }
                } else {
                    \Log::warning('No valid files to sync on create', ['file_ids' => $fileIds, 'button_names' => $buttonNames]);
                }
            } else {
                \Log::warning('No file_ids received or empty array');
            }
        } elseif ($request->has('file_id') && !empty($request->input('file_id'))) {
            // Tek dosya için de pivot table'a kaydet
            $qrCode->files()->sync([$request->input('file_id') => ['sort_order' => 0]]);
        }

        // QR kod oluşturma - Queue'ya gönder (uzun süren işlem)
        // Not: Queue kullanmak için QUEUE_CONNECTION=database veya redis olmalı
        if (config('queue.default') !== 'sync') {
            \App\Jobs\GenerateQrCodeJob::dispatch($qrCode);
        } else {
            // Sync modunda direkt oluştur
            $this->generateQrImage($qrCode);
        }

        // Structured logging
        \Log::info('QR code created', [
            'user_id' => auth()->id(),
            'qr_code_id' => $qrCode->id,
            'type' => $qrCode->qr_type,
            'name' => $qrCode->name,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        ActivityLogHelper::logQrCode('created', $qrCode);

        return redirect()->route('qr-code.show', $qrCode)
            ->with('success', __('common.qr_code_created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(QrCode $qrCode)
    {
        $this->authorize('view', $qrCode);

        $qrCode->load(['user', 'file']);

        // QR kod görseli yoksa oluştur
        if (!$qrCode->file_path || !Storage::disk('public')->exists($qrCode->file_path)) {
            try {
                $qrCode->generateQrImage();
                $qrCode->refresh();
            } catch (\Exception $e) {
                \Log::error('QR code image generation failed in show method', [
                    'qr_code_id' => $qrCode->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Aktivite kayıtlarını çek
        $activityLogs = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('activity_logs')) {
            $activityLogs = \Illuminate\Support\Facades\DB::table('activity_logs')
                ->leftJoin('users', function ($join) {
                    $join->on('activity_logs.causer_id', '=', 'users.id')
                        ->where('activity_logs.causer_type', '=', 'App\Models\User');
                })
                ->where('activity_logs.subject_type', 'App\Models\QrCode')
                ->where('activity_logs.subject_id', $qrCode->id)
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

        return view('pages.qr-code.show', compact('qrCode', 'activityLogs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QrCode $qrCode)
    {
        $this->authorize('update', $qrCode);

        $files = File::latest()
            ->get(); // Tüm dosyaları göster

        $categories = CacheHelper::getActiveCategories();

        // Çoklu dosyaları yükle
        $qrCode->load('files');

        return view('pages.qr-code.edit', compact('qrCode', 'files', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QrCodeRequest $request, QrCode $qrCode)
    {
        $this->authorize('update', $qrCode);

        $data = $request->validated();

        // is_active checkbox işaretli değilse form'dan gönderilmez, bu yüzden manuel olarak false yap
        if (!$request->has('is_active')) {
            $data['is_active'] = false;
        } else {
            $data['is_active'] = (bool) $request->input('is_active');
        }

        // file_id varsa kaydet
        if ($request->has('file_id')) {
            $data['file_id'] = $request->input('file_id');
        }

        // URL tipi için content kontrolü ve format düzeltme
        if ($request->qr_type === 'url' && isset($data['content'])) {
            if (empty($data['content'])) {
                \Log::error('URL tipi QR kod için content boş (update)', ['request' => $request->all()]);
                return back()->withErrors(['content' => __('common.url_content_required')])->withInput();
            }

            // Kullanıcının girdiği değeri olduğu gibi kaydet (otomatik http:// ekleme)
            $data['content'] = trim($data['content']);

            // QR kodun kendi erişim URL'sini engelle (sonsuz döngüyü önle)
            $qrAccessRoute = route('qr.access', 'dummy-token');
            $qrAccessPath = parse_url($qrAccessRoute, PHP_URL_PATH);
            if (strpos($data['content'], $qrAccessPath) !== false || preg_match('/\/qr\/[a-f0-9-]+/i', $data['content'])) {
                \Log::error('URL tipi QR kod için content QR kodun kendi URL\'si olamaz (update)', ['content' => $data['content']]);
                return back()->withErrors(['content' => __('common.url_cannot_be_self')])->withInput();
            }

            \Log::info('URL tipi QR kod güncelleniyor', ['content' => $data['content'], 'qr_type' => $request->qr_type]);
        }

        // Password protection kontrolü
        // Checkbox gönderilmediğinde false olarak kabul et (Laravel checkbox göndermezse)
        $passwordProtected = $request->has('password_protected') && $request->input('password_protected');
        
        if ($passwordProtected) {
            $data['password_protected'] = true;
            // Şifre varsa hash'le
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } elseif (empty($qrCode->password)) {
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
            if ($qrCode->password_protected) {
                $data['password'] = null;
                // Session'ı temizle
                $sessionKey = 'qr_code_' . $qrCode->token . '_verified';
                session()->forget($sessionKey);
            } else {
                // Şifre koruması zaten kapalıydı, şifre alanını değiştirme
                unset($data['password']);
            }
        }

        $qrCode->update($data);
        
        // Model'i yeniden yükle
        $qrCode->refresh();

        // Çoklu dosya varsa pivot table'a kaydet (button_names ile)
        if ($request->qr_type === 'multi_file') {
            $fileIds = $request->input('file_ids', []);
            $buttonNames = $request->input('button_names', []);

            if (is_array($fileIds) && count($fileIds) > 0) {
                $syncData = [];
                $sortOrder = 0;

                // Her dosya için ayrı bir kayıt oluştur (aynı dosya birden fazla kez eklenebilir)
                foreach ($fileIds as $index => $fileId) {
                    if (!empty($fileId)) { // Boş değerleri atla
                        // Aynı dosya birden fazla kez eklenebilir, bu yüzden unique key kullan
                        $uniqueKey = $fileId . '_' . $index;
                        $syncData[$uniqueKey] = [
                            'file_id' => $fileId,
                            'sort_order' => $sortOrder,
                            'button_name' => isset($buttonNames[$index]) && !empty($buttonNames[$index])
                                ? $buttonNames[$index]
                                : 'Dosya ' . ($sortOrder + 1)
                        ];
                        $sortOrder++;
                    }
                }

                if (count($syncData) > 0) {
                    try {
                        // Önce mevcut ilişkileri temizle
                        $qrCode->files()->detach();
                        // Sonra yeni ilişkileri ekle
                        foreach ($syncData as $uniqueKey => $data) {
                            try {
                                $fileId = $data['file_id'];
                                unset($data['file_id']); // file_id'yi pivot data'dan çıkar
                                $qrCode->files()->attach($fileId, $data);
                                \Log::info('File attached on update', ['qr_code_id' => $qrCode->id, 'file_id' => $fileId, 'pivot' => $data]);
                            } catch (\Exception $e) {
                                \Log::error('Failed to attach file on update', [
                                    'qr_code_id' => $qrCode->id,
                                    'file_id' => $data['file_id'] ?? 'unknown',
                                    'error' => $e->getMessage()
                                ]);
                            }
                        }
                        \Log::info('Files synced on update', [
                            'count' => count($syncData),
                            'sync_data' => $syncData,
                            'qr_code_id' => $qrCode->id,
                            'actual_count' => $qrCode->files()->count()
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Failed to sync files on update', [
                            'qr_code_id' => $qrCode->id,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                    }
                } else {
                    \Log::warning('No valid files to sync on update', ['file_ids' => $fileIds, 'button_names' => $buttonNames]);
                }
            }
        } elseif ($request->has('file_id') && !empty($request->input('file_id'))) {
            // Tek dosya için de pivot table'a kaydet
            $qrCode->files()->sync([$request->input('file_id') => ['sort_order' => 0]]);
        }

        if ($request->has('size') || $request->has('format') || $request->has('content')) {
            $this->generateQrImage($qrCode);
        }

        ActivityLogHelper::logQrCode('updated', $qrCode);

        return redirect()->route('qr-code.show', $qrCode)
            ->with('success', __('common.qr_code_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QrCode $qrCode)
    {
        $this->authorize('delete', $qrCode);

        ActivityLogHelper::logQrCode('deleted', $qrCode);

        if ($qrCode->file_path && Storage::disk('public')->exists($qrCode->file_path)) {
            Storage::disk('public')->delete($qrCode->file_path);
        }

        $qrCode->delete();

        return redirect()->route('qr-code.index')
            ->with('success', __('common.qr_code_deleted'));
    }

    /**
     * Download QR code image.
     */
    public function download(QrCode $qrCode)
    {
        $this->authorize('view', $qrCode);

        $qrCode->increment('download_count');

        ActivityLogHelper::logQrCode('downloaded', $qrCode);

        if (!$qrCode->file_path || !Storage::disk('public')->exists($qrCode->file_path)) {
            $this->generateQrImage($qrCode);
        }

        if (Storage::disk('public')->exists($qrCode->file_path)) {
            return Storage::disk('public')->download(
                $qrCode->file_path,
                $qrCode->name . '.' . $qrCode->format
            );
        }

        abort(404, 'QR kod görseli bulunamadı.');
    }

    /**
     * Generate QR code image.
     */
    public function generateQrImage(QrCode $qrCode)
    {
        $content = $qrCode->content;
        $size = $qrCode->size;
        $format = $qrCode->format;

        $fileName = 'qr-codes/' . $qrCode->token . '.' . $format;

        try {
            if ($format === 'svg') {
                $qrImage = QrCodeGenerator::size($size)
                    ->format('svg')
                    ->generate($content);
            } else {
                // PNG formatı için imagick kontrolü
                if (extension_loaded('imagick')) {
                    // Imagick yüklüyse PNG oluştur
                    $qrImage = QrCodeGenerator::size($size)
                        ->format('png')
                        ->errorCorrection('H')
                        ->generate($content);
                } else {
                    // Imagick yoksa SVG kullan ve format'ı güncelle
                    \Log::warning('PNG format requested but imagick not available, using SVG instead');
                    $format = 'svg';
                    $fileName = 'qr-codes/' . $qrCode->token . '.' . $format;
                    $qrImage = QrCodeGenerator::size($size)
                        ->format('svg')
                        ->generate($content);
                }
            }
        } catch (\Exception $e) {
            // Imagick hatası varsa SVG kullan
            if (strpos($e->getMessage(), 'imagick') !== false || strpos($e->getMessage(), 'Imagick') !== false) {
                \Log::warning('Imagick error, using SVG: ' . $e->getMessage());
                $format = 'svg';
                $fileName = 'qr-codes/' . $qrCode->token . '.' . $format;
                $qrImage = QrCodeGenerator::size($size)
                    ->format('svg')
                    ->generate($content);
            } else {
                // Diğer hatalar için tekrar dene
                throw $e;
            }
        }

        Storage::disk('public')->put($fileName, $qrImage);

        $qrCode->file_path = $fileName;
        $qrCode->format = $format;
        $qrCode->save();
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'ids' => 'required|array',
            'ids.*' => 'exists:qr_codes,id',
        ]);

        $ids = $request->ids;
        $action = $request->action;

        \DB::beginTransaction();
        try {
            switch ($action) {
                case 'delete':
                    QrCode::whereIn('id', $ids)
                        ->where('user_id', auth()->id())
                        ->delete();
                    break;
                case 'activate':
                    QrCode::whereIn('id', $ids)
                        ->where('user_id', auth()->id())
                        ->update(['is_active' => true]);
                    break;
                case 'deactivate':
                    QrCode::whereIn('id', $ids)
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
