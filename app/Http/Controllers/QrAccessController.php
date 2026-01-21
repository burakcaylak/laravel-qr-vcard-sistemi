<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QrAccessController extends Controller
{
    /**
     * QR kod token ile dosyaya erişim
     * Bu route herkese açık olabilir (middleware yok)
     */
    public function access($token)
    {
        $qrCode = QrCode::with(['file', 'files'])
            ->where('token', $token)
            ->where('is_active', true) // Sadece aktif QR kodları getir
            ->first();
        
        if (!$qrCode) {
            abort(404, __('common.qr_code_not_found'));
        }
        
        // Model'i veritabanından yeniden yükle (cache sorununu önlemek için)
        $qrCode->refresh();
        
        // Ekstra kontrol (güvenlik için)
        if (!$qrCode->is_active) {
            abort(403, 'QR kod aktif değil.');
        }
        
        if ($qrCode->is_expired) {
            abort(410, 'QR kodun süresi dolmuş.');
        }

        // Şifre kontrolü
        if ($qrCode->password_protected) {
            $sessionKey = 'qr_code_' . $token . '_verified';
            if (!session()->has($sessionKey)) {
                return redirect()->route('qr.password', $token);
            }
        }
        
        $qrCode->increment('scan_count');
        
        // QR tipine göre işlem yap
        if ($qrCode->qr_type === 'multi_file') {
            // Çoklu dosya için özel sayfa
            $files = $qrCode->files;
            return view('pages.qr-code.access-multi', [
                'qrCode' => $qrCode,
                'files' => $files,
            ]);
        } elseif ($qrCode->qr_type === 'file') {
            // Tek dosya için direkt dosyayı aç/indir
            $file = null;
            $files = $qrCode->files;
            
            if (count($files) > 0) {
                // İlk dosyayı al
                $file = $files->first();
            } elseif ($qrCode->file) {
                // Eski yapı (file_id ile)
                $file = $qrCode->file;
            }
            
            if (!$file) {
                abort(404, 'Dosya bulunamadı.');
            }
            
            // Dosya path'ini kontrol et, yoksa alternatif path'leri dene
            $filePath = $file->path;
            if (!Storage::disk('public')->exists($filePath)) {
                // Alternatif path'leri dene
                $alternatives = [
                    'settings/' . basename($filePath),
                    'files/' . basename($filePath),
                    $filePath
                ];
                
                $found = false;
                foreach ($alternatives as $altPath) {
                    if (Storage::disk('public')->exists($altPath)) {
                        $filePath = $altPath;
                        $found = true;
                        break;
                    }
                }
                
                // Hala bulunamadıysa, dosya adına göre tüm storage'da ara
                if (!$found) {
                    $fileName = basename($filePath);
                    $originalName = $file->original_name ?? $fileName;
                    
                    // Normalize dosya adları (Türkçe karakterleri ve case-insensitive)
                    $normalizeFileName = function($name) {
                        $name = mb_strtolower($name, 'UTF-8');
                        $name = str_replace(['ı', 'ğ', 'ü', 'ş', 'ö', 'ç'], ['i', 'g', 'u', 's', 'o', 'c'], $name);
                        return $name;
                    };
                    
                    $normalizedTarget = $normalizeFileName($fileName);
                    $normalizedOriginal = $normalizeFileName($originalName);
                    
                    $allFiles = Storage::disk('public')->allFiles();
                    foreach ($allFiles as $storageFile) {
                        $storageFileName = basename($storageFile);
                        $normalizedStorage = $normalizeFileName($storageFileName);
                        
                        // Hem dosya adı hem de orijinal ad ile karşılaştır
                        if ($normalizedStorage === $normalizedTarget || $normalizedStorage === $normalizedOriginal) {
                            $filePath = $storageFile;
                            $found = true;
                            break;
                        }
                    }
                }
                
                if (!$found) {
                    return response()->view('pages.qr-code.access-error', [
                        'qrCode' => $qrCode,
                        'error' => 'Dosya bulunamadı. Dosya silinmiş veya taşınmış olabilir.',
                        'file' => $file
                    ], 404);
                }
            }
            
            $file->increment('download_count');
            
            // Dosya path'ini absolute path'e çevir
            $actualFilePath = Storage::disk('public')->path($filePath);
            $mimeType = $file->mime_type ?? Storage::disk('public')->mimeType($filePath);
            
            // Dosyanın gerçekten var olduğunu kontrol et
            if (!file_exists($actualFilePath)) {
                return response()->view('pages.qr-code.access-error', [
                    'qrCode' => $qrCode,
                    'error' => 'Dosya bulunamadı. Dosya silinmiş veya taşınmış olabilir.',
                    'file' => $file
                ], 404);
            }
            
            // PDF dosyaları için görüntüleme, diğerleri için indirme
            if ($mimeType === 'application/pdf' || strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION)) === 'pdf') {
                return response()->file($actualFilePath, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $file->original_name . '"'
                ]);
            }
            
            // Diğer dosyalar için indirme - bulunan path'i kullan
            return Storage::disk('public')->download(
                $filePath,
                $file->original_name
            );
        }
        
        // URL veya diğer tipler için - içeriği göster veya yönlendir
        if ($qrCode->qr_type === 'url') {
            // URL tipi için dosya kontrolü
            $file = null;
            $files = $qrCode->files;
            
            if (count($files) > 0) {
                // İlk dosyayı al
                $file = $files->first();
            } elseif ($qrCode->file) {
                // Eski yapı (file_id ile)
                $file = $qrCode->file;
            }
            
            // URL formatını düzelt - http:// veya https:// yoksa ekle
            $url = null;
            if (!empty($qrCode->content)) {
                $url = trim($qrCode->content);
                if (!preg_match('/^https?:\/\//i', $url)) {
                    $url = 'http://' . $url;
                }
            }
            
            // URL tipi için sayfa göster (içerik ve görüntüle butonu ile)
            // Eğer dosya varsa buton dosyayı açacak, yoksa URL'yi açacak
            return view('pages.qr-code.access', [
                'qrCode' => $qrCode,
                'files' => collect(),
                'content' => $qrCode->content, // Orijinal içerik
                'url' => $url, // Formatlanmış URL (buton için, dosya yoksa)
                'file' => $file // Dosya (varsa)
            ]);
        }
        
        // Diğer tipler için içerik göster
        return view('pages.qr-code.access', [
            'qrCode' => $qrCode,
            'files' => collect(),
            'content' => $qrCode->content
        ]);
    }

    /**
     * QR kod token ile dosya indirme
     * Bu route herkese açık olabilir (middleware yok)
     */
    public function downloadFile($token, $fileId)
    {
        $qrCode = QrCode::with('files')
            ->where('token', $token)
            ->where('is_active', true)
            ->first();
        
        if (!$qrCode) {
            abort(404, 'QR kod bulunamadı veya aktif değil.');
        }
        
        if ($qrCode->is_expired) {
            abort(410, 'QR kodun süresi dolmuş.');
        }
        
        // Dosyanın bu QR kod ile ilişkili olup olmadığını kontrol et
        $file = $qrCode->files()->where('files.id', $fileId)->first();
        
        if (!$file) {
            // Eski yapı için file_id kontrolü
            if ($qrCode->file_id == $fileId) {
                $file = $qrCode->file;
            } else {
                abort(404, 'Dosya bulunamadı veya bu QR kod ile ilişkili değil.');
            }
        }
        
        // Dosya path'ini kontrol et, yoksa alternatif path'leri dene
        $filePath = $file->path;
        if (!Storage::disk('public')->exists($filePath)) {
            // Alternatif path'leri dene
            $alternatives = [
                'settings/' . basename($filePath),
                'files/' . basename($filePath),
                $filePath
            ];
            
            $found = false;
            foreach ($alternatives as $altPath) {
                if (Storage::disk('public')->exists($altPath)) {
                    $filePath = $altPath;
                    $found = true;
                    break;
                }
            }
            
            // Hala bulunamadıysa, dosya adına göre tüm storage'da ara
            if (!$found) {
                $fileName = basename($filePath);
                $originalName = $file->original_name ?? $fileName;
                
                // Normalize dosya adları (Türkçe karakterleri ve case-insensitive)
                $normalizeFileName = function($name) {
                    $name = mb_strtolower($name, 'UTF-8');
                    $name = str_replace(['ı', 'ğ', 'ü', 'ş', 'ö', 'ç'], ['i', 'g', 'u', 's', 'o', 'c'], $name);
                    return $name;
                };
                
                $normalizedTarget = $normalizeFileName($fileName);
                $normalizedOriginal = $normalizeFileName($originalName);
                
                $allFiles = Storage::disk('public')->allFiles();
                foreach ($allFiles as $storageFile) {
                    $storageFileName = basename($storageFile);
                    $normalizedStorage = $normalizeFileName($storageFileName);
                    
                    if ($normalizedStorage === $normalizedTarget || $normalizedStorage === $normalizedOriginal) {
                        $filePath = $storageFile;
                        $found = true;
                        break;
                    }
                }
            }
            
            if (!$found) {
                abort(404, 'Dosya bulunamadı.');
            }
        }
        
        $file->increment('download_count');
        
        // Dosya path'ini absolute path'e çevir
        $actualFilePath = Storage::disk('public')->path($filePath);
        $mimeType = $file->mime_type ?? Storage::disk('public')->mimeType($filePath);
        
        // PDF dosyaları için görüntüleme, diğerleri için indirme
        if ($mimeType === 'application/pdf' || strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION)) === 'pdf') {
            return response()->file($actualFilePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $file->original_name . '"'
            ]);
        }
        
        // Diğer dosyalar için indirme - bulunan path'i kullan
        return Storage::disk('public')->download(
            $filePath,
            $file->original_name
        );
    }

    public function password($token)
    {
        $qrCode = QrCode::where('token', $token)
            ->where('is_active', true)
            ->first();

        if (!$qrCode) {
            abort(404, __('common.qr_code_not_found'));
        }

        if (!$qrCode->password_protected) {
            return redirect()->route('qr.access', $token);
        }

        return view('pages.qr-code.password', compact('qrCode'));
    }

    public function verifyPassword(Request $request, $token)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $qrCode = QrCode::where('token', $token)
            ->where('is_active', true)
            ->first();

        if (!$qrCode) {
            abort(404, __('common.qr_code_not_found'));
        }

        if ($qrCode->verifyPassword($request->password)) {
            session(['qr_code_' . $token . '_verified' => true]);
            return redirect()->route('qr.access', $token);
        }

        return back()->withErrors(['password' => __('common.invalid_password')]);
    }
}
