<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogHelper;
use App\Models\File;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MediaLibraryController extends Controller
{
    /**
     * CORS header'ları ile JSON response döndür
     */
    private function jsonResponse($data, $status = 200)
    {
        return response()->json($data, $status, [
            'Access-Control-Allow-Origin' => request()->header('Origin', '*'),
            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers' => 'Content-Type, X-CSRF-TOKEN, X-Requested-With, Accept, Authorization',
            'Access-Control-Allow-Credentials' => 'true',
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    
    public function checkFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'folder' => 'nullable|string',
        ]);
        
        $file = $request->file('file');
        $folder = $request->input('folder', 'files');
        
        // Klasör kontrolü
        if (!in_array($folder, ['settings', 'files'])) {
            $folder = 'files';
        }
        
        $originalName = $file->getClientOriginalName();
        $targetPath = $folder . '/' . $originalName;
        
        $exists = Storage::disk('public')->exists($targetPath);
        
        return $this->jsonResponse([
            'file_exists' => $exists,
            'file_name' => $originalName,
            'file_path' => $targetPath,
        ]);
    }

    public function index(Request $request)
    {
        // Önce File modelindeki dosyaları al
        $query = File::with(['user', 'category']);
        
        // Filtreleme
        $filterType = $request->get('type', 'all');
        $filterCategory = $request->get('category', 'all');
        $search = $request->get('search', '');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('original_name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        
        if ($filterType !== 'all') {
            $query->where('type', $filterType);
        }
        
        if ($filterCategory !== 'all') {
            $query->where('category_id', $filterCategory);
        }
        
        // Dosyaları al ve formatla
        $files = $query->orderBy('created_at', 'desc')->get();
        
        $mediaFiles = [];
        foreach ($files as $file) {
            $mediaFiles[] = [
                'id' => $file->id,
                'path' => $file->path,
                'name' => $file->name,
                'original_name' => $file->original_name,
                'size' => $file->size,
                'mime_type' => $file->mime_type,
                'type' => $file->type,
                'description' => $file->description,
                'category_id' => $file->category_id,
                'category' => $file->category ? $file->category->name : null,
                'is_public' => $file->is_public,
                'download_count' => $file->download_count,
                'user' => $file->user ? $file->user->name : null,
                'created_at' => $file->created_at,
                'url' => $file->url,
                'folder' => dirname($file->path),
            ];
        }
        
        // Storage'da olup File modelinde olmayan dosyaları da ekle (settings klasörü için - sadece kategori filtresi yoksa)
        if ($filterCategory === 'all') {
            $allFiles = Storage::disk('public')->allFiles();
            foreach ($allFiles as $filePath) {
                if (Str::startsWith($filePath, 'settings/')) {
                    // File modelinde var mı kontrol et
                    $existsInDb = File::where('path', $filePath)->exists();
                    if (!$existsInDb) {
                        try {
                            $mediaFiles[] = [
                                'id' => null,
                                'path' => $filePath,
                                'name' => basename($filePath),
                                'original_name' => basename($filePath),
                                'size' => Storage::disk('public')->size($filePath),
                                'mime_type' => Storage::disk('public')->mimeType($filePath),
                                'type' => $this->getFileType($filePath),
                                'description' => null,
                                'category_id' => null,
                                'category' => null,
                                'is_public' => false,
                                'download_count' => 0,
                                'user' => null,
                                'created_at' => date('Y-m-d H:i:s', Storage::disk('public')->lastModified($filePath)),
                                'last_modified' => Storage::disk('public')->lastModified($filePath),
                                'url' => asset('storage/' . implode('/', array_map('rawurlencode', explode('/', $filePath)))),
                                'folder' => dirname($filePath),
                            ];
                        } catch (\Exception $e) {
                            continue;
                        }
                    }
                }
            }
        }
        
        // Kategoriler
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        
        // JSON response isteniyorsa
        if ($request->expectsJson() || $request->wantsJson()) {
            return $this->jsonResponse([
                'success' => true,
                'files' => array_values($mediaFiles),
            ]);
        }
        
        return view('pages.media-library.index', compact('mediaFiles', 'categories', 'filterType', 'filterCategory', 'search'));
    }
    
    public function store(Request $request)
    {
        // Büyük dosyalar için execution time limit'i artır
        set_time_limit(600); // 10 dakika
        ini_set('max_execution_time', 600);
        
        try {
            $request->validate([
                'file' => 'required|file|max:' . config('files.max_file_size'),
                'name' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'category_id' => 'nullable|exists:categories,id',
                'is_public' => 'nullable|boolean',
                'overwrite' => 'nullable|boolean',
            ]);
        } catch (ValidationException $e) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Validation hatası',
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;
        }
        
        $file = $request->file('file');
        
        if (!$file) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Dosya bulunamadı. Lütfen bir dosya seçin.',
            ], 422);
        }
        
        // Kategoriye göre klasör belirleme - varsayılan olarak 'files' klasörü
        $folder = 'files';
        
        // Orijinal dosya adını kullan
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        
        // Dosya adını güvenli hale getir (özel karakterleri temizle, boşlukları alt çizgi ile değiştir)
        $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
        $safeName = preg_replace('/_+/', '_', $safeName); // Birden fazla alt çizgiyi tek yap
        $safeName = trim($safeName, '_'); // Baştan ve sondan alt çizgileri temizle
        
        // Eğer safe name boşsa, timestamp kullan
        if (empty($safeName)) {
            $safeName = 'file_' . time();
        }
        
        // Dosya adı belirleme
        if ($request->input('name')) {
            $customName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $request->input('name'));
            $customName = preg_replace('/_+/', '_', $customName);
            $customName = trim($customName, '_');
            $fileName = $customName . '.' . $extension;
        } else {
            $fileName = $safeName . '.' . $extension;
        }
        
        // Eğer aynı isimde dosya varsa, timestamp ekle
        $targetPath = $folder . '/' . $fileName;
        $baseName = pathinfo($fileName, PATHINFO_FILENAME);
        $counter = 1;
        while (Storage::disk('public')->exists($targetPath)) {
            $fileName = $baseName . '_' . $counter . '.' . $extension;
            $targetPath = $folder . '/' . $fileName;
            $counter++;
        }
        
        // Aynı isimde dosya var mı kontrol et
        $existingFile = File::where('path', $targetPath)->first();
        
        if ($existingFile && !$request->boolean('overwrite')) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Aynı isimde bir dosya zaten mevcut.',
                'file_exists' => true,
                'file_name' => $fileName,
                'file_path' => $targetPath,
            ], 422);
        }
        
        // Overwrite varsa eski dosyayı sil
        if ($existingFile && $request->boolean('overwrite')) {
            if (Storage::disk('public')->exists($existingFile->path)) {
                Storage::disk('public')->delete($existingFile->path);
            }
            $existingFile->delete();
        }
        
        // MIME type kontrolü
        $mimeType = $file->getMimeType();
        $allowedMimes = array_merge(
            config('files.allowed_mime_types.image', []),
            config('files.allowed_mime_types.document', []),
            config('files.allowed_mime_types.video', []),
            config('files.allowed_mime_types.audio', [])
        );
        
        if (!in_array($mimeType, $allowedMimes)) {
            return $this->jsonResponse([
                'success' => false,
                'message' => __('common.invalid_file_type'),
            ], 422);
        }
        
        // Virus scanning (eğer etkinse)
        if (config('files.enable_virus_scanning', false)) {
            $tempPath = $file->getRealPath();
            $scanResult = \App\Services\VirusScannerService::scan($tempPath);
            
            if (!$scanResult['clean']) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => __('common.virus_detected') . ': ' . $scanResult['message'],
                ], 422);
            }
        }
        
        // Dosyayı kaydet
        try {
            $path = $file->storeAs($folder, $fileName, 'public');
            
            if (!$path) {
                \Log::error('File upload failed: storeAs returned false', [
                    'folder' => $folder,
                    'fileName' => $fileName,
                    'originalName' => $originalName,
                ]);
                
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Dosya kaydedilemedi. Lütfen tekrar deneyin.',
                ], 500);
            }
            
            // Dosyanın gerçekten kaydedildiğini kontrol et
            if (!Storage::disk('public')->exists($path)) {
                \Log::error('File upload failed: File does not exist after storeAs', [
                    'path' => $path,
                    'folder' => $folder,
                    'fileName' => $fileName,
                ]);
                
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Dosya kaydedilemedi. Lütfen tekrar deneyin.',
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('File upload exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'folder' => $folder,
                'fileName' => $fileName,
            ]);
            
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Dosya yüklenirken bir hata oluştu: ' . $e->getMessage(),
            ], 500);
        }
        
        // Name alanını belirle - her zaman bir değer olmalı
        $name = $request->input('name');
        if (empty($name) || trim($name) === '') {
            // Name boşsa, orijinal dosya adından uzantısız adı al
            $name = pathinfo($originalName, PATHINFO_FILENAME);
            // Eğer pathinfo boş dönerse, orijinal adı kullan
            if (empty($name) || trim($name) === '') {
                $name = $originalName;
            }
        }
        
        // File modelinde kayıt oluştur veya güncelle
        $fileModel = File::create([
            'user_id' => auth()->id(),
            'name' => trim($name), // Trim ile boşlukları temizle
            'original_name' => $originalName,
            'path' => $path,
            'type' => $this->getFileType($path),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'description' => $request->input('description'),
            'category_id' => $request->input('category_id'),
            'is_public' => $request->boolean('is_public', false),
        ]);
        
        ActivityLogHelper::logFile('created', $fileModel);
        
        // Büyük dosyalar için queue'ya gönder (thumbnail generation)
        if ($fileModel->size > 1024 * 1024 && in_array($fileModel->type, ['image'])) { // 1MB'dan büyük görseller
            if (config('queue.default') !== 'sync') {
                \App\Jobs\ProcessFileUploadJob::dispatch($fileModel);
            }
        }
        
        // Structured logging
        \Log::info('File uploaded', [
            'user_id' => auth()->id(),
            'file_id' => $fileModel->id,
            'file_name' => $fileModel->name,
            'file_type' => $fileModel->type,
            'file_size' => $fileModel->size,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        if ($request->expectsJson() || $request->wantsJson()) {
            $responseData = [
                'success' => true,
                'message' => 'Dosya başarıyla yüklendi.',
                'file' => [
                    'id' => $fileModel->id,
                    'name' => $fileModel->name,
                    'original_name' => $fileModel->original_name,
                    'path' => $fileModel->path,
                    'type' => $fileModel->type,
                    'mime_type' => $fileModel->mime_type,
                    'size' => $fileModel->size,
                    'url' => $fileModel->url,
                ],
                'id' => $fileModel->id,
            ];
            
            \Log::info('Sending JSON response for file upload', [
                'file_id' => $fileModel->id,
                'response_size' => strlen(json_encode($responseData)),
            ]);
            
            return $this->jsonResponse($responseData, 200);
        }
        
        return redirect()->route('media-library.index')->with('success', __('common.media_uploaded'));
    }
    
    private function getFileType($file)
    {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        
        $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'ico'];
        $videoTypes = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'];
        $audioTypes = ['mp3', 'wav', 'ogg', 'm4a'];
        $documentTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];
        
        if (in_array($extension, $imageTypes)) {
            return 'image';
        } elseif (in_array($extension, $videoTypes)) {
            return 'video';
        } elseif (in_array($extension, $audioTypes)) {
            return 'audio';
        } elseif (in_array($extension, $documentTypes)) {
            return 'document';
        }
        
        return 'other';
    }
    
    public function destroy(Request $request, $path)
    {
        $decodedPath = urldecode($path);
        
        // File modelinde kayıt var mı kontrol et
        $file = File::where('path', $decodedPath)->first();
        
        if ($file) {
            // File modelinden sil
            ActivityLogHelper::logFile('deleted', $file);
            
            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }
            
            $file->delete();
            
            if ($request->expectsJson()) {
                return $this->jsonResponse([
                    'success' => true,
                    'message' => 'Dosya başarıyla silindi.',
                ]);
            }
            
            return redirect()->route('media-library.index')->with('success', __('common.media_deleted'));
        }
        
        // File modelinde yoksa sadece storage'dan sil
        if (Storage::disk('public')->exists($decodedPath)) {
            $fileName = basename($decodedPath);
            $folder = dirname($decodedPath);
            
            ActivityLogHelper::logMedia('deleted', $fileName, $folder);
            
            Storage::disk('public')->delete($decodedPath);
            
            if ($request->expectsJson()) {
                return $this->jsonResponse([
                    'success' => true,
                    'message' => 'Dosya başarıyla silindi.',
                ]);
            }
            
            return redirect()->route('media-library.index')->with('success', __('common.media_deleted'));
        }
        
        if ($request->expectsJson()) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Dosya bulunamadı.',
            ], 404);
        }
        
        return redirect()->route('media-library.index')->with('error', __('common.file_not_found'));
    }
    
    public function show($id)
    {
        $file = File::findOrFail($id);
        $this->authorize('view', $file);
        
        $file->load(['user', 'category', 'qrCodes']);
        
        return view('pages.media-library.show', compact('file'));
    }
    
    public function edit($id)
    {
        $file = File::findOrFail($id);
        $this->authorize('update', $file);
        
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        
        return view('pages.media-library.edit', compact('file', 'categories'));
    }
    
    public function update(Request $request, $id)
    {
        $file = File::findOrFail($id);
        $this->authorize('update', $file);
        
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:categories,id',
            'is_public' => 'nullable|boolean',
        ]);
        
        $file->update($request->only(['name', 'description', 'category_id', 'is_public']));
        
        ActivityLogHelper::logFile('updated', $file);
        
        if ($request->expectsJson()) {
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Dosya başarıyla güncellendi.',
                'file' => $file,
            ]);
        }
        
        return redirect()->route('media-library.show', $file)
            ->with('success', __('common.file_updated'));
    }
    
    /**
     * Download the specified file.
     */
    public function download($id)
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
        
        $file = File::findOrFail($id);
        
        // Yetki kontrolü (isteğe bağlı)
        // $this->authorize('view', $file);
        
        if (Storage::disk('public')->exists($file->path)) {
            $file->increment('download_count');
            
            ActivityLogHelper::logFile('downloaded', $file);
            
            $filePath = Storage::disk('public')->path($file->path);
            $fileName = $file->original_name;
            
            return response()->download($filePath, $fileName, [
                'Access-Control-Allow-Origin' => request()->header('Origin', '*'),
                'Access-Control-Allow-Methods' => 'GET, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, X-Requested-With, Accept, Authorization',
                'Access-Control-Allow-Credentials' => 'true',
            ]);
        }
        
        abort(404, 'Dosya bulunamadı.');
    }
}

