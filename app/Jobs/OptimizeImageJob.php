<?php

namespace App\Jobs;

use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class OptimizeImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 dakika timeout
    public $tries = 3; // 3 kez deneme

    protected $file;

    /**
     * Create a new job instance.
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Sadece görseller için optimize et
            if ($this->file->type !== 'image') {
                Log::info('File is not an image, skipping optimization', [
                    'file_id' => $this->file->id,
                    'file_type' => $this->file->type,
                ]);
                return;
            }

            $fullPath = storage_path('app/public/' . $this->file->path);
            
            if (!file_exists($fullPath)) {
                Log::warning('File not found for optimization', [
                    'file_id' => $this->file->id,
                    'path' => $fullPath,
                ]);
                return;
            }

            $manager = new ImageManager(new Driver());
            $image = $manager->read($fullPath);
            
            // WebP formatına çevir (daha küçük boyut)
            $webpPath = str_replace(
                '.' . pathinfo($this->file->path, PATHINFO_EXTENSION),
                '.webp',
                $this->file->path
            );
            
            $webpFullPath = storage_path('app/public/' . $webpPath);
            $directory = dirname($webpFullPath);
            
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // WebP olarak kaydet (quality: 85)
            $image->toWebp(85)->save($webpFullPath);
            
            // Orijinal dosya boyutunu kontrol et
            $originalSize = filesize($fullPath);
            $webpSize = filesize($webpFullPath);
            
            // Eğer WebP daha küçükse, orijinali WebP ile değiştir
            if ($webpSize < $originalSize) {
                // Orijinal dosyayı yedekle
                $backupPath = str_replace(
                    '.' . pathinfo($this->file->path, PATHINFO_EXTENSION),
                    '_original.' . pathinfo($this->file->path, PATHINFO_EXTENSION),
                    $this->file->path
                );
                
                Storage::disk('public')->copy($this->file->path, $backupPath);
                
                // WebP'yi orijinal path'e kopyala
                Storage::disk('public')->copy($webpPath, $this->file->path);
                
                // Geçici WebP dosyasını sil
                Storage::disk('public')->delete($webpPath);
                
                // File modelini güncelle
                $this->file->update([
                    'size' => $webpSize,
                    'mime_type' => 'image/webp',
                ]);
                
                Log::info('Image optimized successfully', [
                    'file_id' => $this->file->id,
                    'original_size' => $originalSize,
                    'optimized_size' => $webpSize,
                    'saved_bytes' => $originalSize - $webpSize,
                ]);
            } else {
                // WebP daha büyükse, WebP dosyasını sil
                Storage::disk('public')->delete($webpPath);
                
                Log::info('WebP conversion did not reduce size, keeping original', [
                    'file_id' => $this->file->id,
                    'original_size' => $originalSize,
                    'webp_size' => $webpSize,
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Image optimization failed', [
                'file_id' => $this->file->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            throw $e; // Retry için exception fırlat
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Image optimization job failed permanently', [
            'file_id' => $this->file->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
