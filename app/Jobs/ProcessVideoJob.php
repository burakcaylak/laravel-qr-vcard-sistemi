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

class ProcessVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1800; // 30 dakika timeout (video işleme uzun sürebilir)
    public $tries = 2; // 2 kez deneme

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
            // Sadece videolar için işle
            if ($this->file->type !== 'video') {
                Log::info('File is not a video, skipping processing', [
                    'file_id' => $this->file->id,
                    'file_type' => $this->file->type,
                ]);
                return;
            }

            $fullPath = storage_path('app/public/' . $this->file->path);
            
            if (!file_exists($fullPath)) {
                Log::warning('Video file not found', [
                    'file_id' => $this->file->id,
                    'path' => $fullPath,
                ]);
                return;
            }

            // Video thumbnail oluştur (ilk frame)
            $this->generateVideoThumbnail($fullPath);
            
            // Video metadata'sını kaydet
            $this->extractVideoMetadata($fullPath);
            
            Log::info('Video processed successfully', [
                'file_id' => $this->file->id,
                'file_name' => $this->file->name,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Video processing failed', [
                'file_id' => $this->file->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Video işleme kritik değil, exception fırlatma (retry yapma)
        }
    }

    /**
     * Generate thumbnail from video (first frame)
     */
    private function generateVideoThumbnail(string $videoPath): void
    {
        try {
            // FFmpeg kullanarak thumbnail oluştur (eğer yüklüyse)
            $thumbnailPath = str_replace(
                '.' . pathinfo($this->file->path, PATHINFO_EXTENSION),
                '_thumb.jpg',
                $this->file->path
            );
            
            $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);
            $directory = dirname($thumbnailFullPath);
            
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // FFmpeg komutu (eğer sistemde FFmpeg varsa)
            $ffmpegPath = config('files.ffmpeg_path', 'ffmpeg');
            
            if (shell_exec("which {$ffmpegPath}")) {
                $command = sprintf(
                    '%s -i %s -ss 00:00:01 -vframes 1 -q:v 2 %s 2>&1',
                    escapeshellarg($ffmpegPath),
                    escapeshellarg($videoPath),
                    escapeshellarg($thumbnailFullPath)
                );
                
                exec($command, $output, $returnCode);
                
                if ($returnCode === 0 && file_exists($thumbnailFullPath)) {
                    Log::info('Video thumbnail generated', [
                        'file_id' => $this->file->id,
                        'thumbnail_path' => $thumbnailPath,
                    ]);
                } else {
                    Log::warning('Video thumbnail generation failed', [
                        'file_id' => $this->file->id,
                        'output' => implode("\n", $output),
                    ]);
                }
            } else {
                Log::info('FFmpeg not found, skipping video thumbnail generation', [
                    'file_id' => $this->file->id,
                ]);
            }
            
        } catch (\Exception $e) {
            Log::warning('Video thumbnail generation error', [
                'file_id' => $this->file->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Extract video metadata
     */
    private function extractVideoMetadata(string $videoPath): void
    {
        try {
            // getID3 kütüphanesi kullanarak metadata çıkar (eğer yüklüyse)
            // Şimdilik sadece log'a yazıyoruz
            Log::info('Video metadata extraction', [
                'file_id' => $this->file->id,
                'file_size' => filesize($videoPath),
                'mime_type' => $this->file->mime_type,
            ]);
            
        } catch (\Exception $e) {
            Log::warning('Video metadata extraction error', [
                'file_id' => $this->file->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Video processing job failed permanently', [
            'file_id' => $this->file->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
