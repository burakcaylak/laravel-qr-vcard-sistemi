<?php

namespace App\Jobs;

use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProcessFileUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
            // Büyük görseller için thumbnail oluştur
            if (in_array($this->file->type, ['image']) && $this->file->size > 1024 * 1024) { // 1MB'dan büyükse
                $this->generateThumbnail();
            }
            
            Log::info('File processed successfully', [
                'file_id' => $this->file->id,
                'file_name' => $this->file->name,
                'file_type' => $this->file->type,
            ]);
        } catch (\Exception $e) {
            Log::error('File processing failed', [
                'file_id' => $this->file->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
    
    /**
     * Generate thumbnail for image
     */
    private function generateThumbnail(): void
    {
        $fullPath = storage_path('app/public/' . $this->file->path);
        
        if (!file_exists($fullPath)) {
            return;
        }
        
        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($fullPath);
            
            // Thumbnail oluştur (300x300 max)
            $image->scale(width: 300, height: 300);
            
            $thumbnailPath = str_replace(
                pathinfo($this->file->path, PATHINFO_DIRNAME),
                pathinfo($this->file->path, PATHINFO_DIRNAME) . '/thumbnails',
                $this->file->path
            );
            
            $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);
            $directory = dirname($thumbnailFullPath);
            
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $image->save($thumbnailFullPath);
        } catch (\Exception $e) {
            Log::warning('Thumbnail generation failed', [
                'file_id' => $this->file->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
