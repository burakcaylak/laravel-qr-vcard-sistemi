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

class ProcessPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600; // 10 dakika timeout
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
            // Sadece PDF'ler için işle
            if ($this->file->type !== 'document' || $this->file->mime_type !== 'application/pdf') {
                Log::info('File is not a PDF, skipping processing', [
                    'file_id' => $this->file->id,
                    'file_type' => $this->file->type,
                    'mime_type' => $this->file->mime_type,
                ]);
                return;
            }

            $fullPath = storage_path('app/public/' . $this->file->path);
            
            if (!file_exists($fullPath)) {
                Log::warning('PDF file not found', [
                    'file_id' => $this->file->id,
                    'path' => $fullPath,
                ]);
                return;
            }

            // PDF thumbnail oluştur (ilk sayfa)
            $this->generatePdfThumbnail($fullPath);
            
            // PDF metadata'sını çıkar
            $this->extractPdfMetadata($fullPath);
            
            Log::info('PDF processed successfully', [
                'file_id' => $this->file->id,
                'file_name' => $this->file->name,
            ]);
            
        } catch (\Exception $e) {
            Log::error('PDF processing failed', [
                'file_id' => $this->file->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            throw $e; // Retry için exception fırlat
        }
    }

    /**
     * Generate thumbnail from PDF (first page)
     */
    private function generatePdfThumbnail(string $pdfPath): void
    {
        try {
            // Imagick veya Ghostscript kullanarak PDF'den görsel oluştur
            $thumbnailPath = str_replace(
                '.pdf',
                '_thumb.jpg',
                $this->file->path
            );
            
            $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);
            $directory = dirname($thumbnailFullPath);
            
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Imagick kullanarak PDF'den görsel oluştur
            if (extension_loaded('imagick')) {
                $imagick = new \Imagick();
                $imagick->setResolution(150, 150);
                $imagick->readImage($pdfPath . '[0]'); // İlk sayfa
                $imagick->setImageFormat('jpeg');
                $imagick->setImageCompressionQuality(85);
                $imagick->scaleImage(800, 0); // Genişlik 800px, yükseklik otomatik
                $imagick->writeImage($thumbnailFullPath);
                $imagick->clear();
                $imagick->destroy();
                
                Log::info('PDF thumbnail generated with Imagick', [
                    'file_id' => $this->file->id,
                    'thumbnail_path' => $thumbnailPath,
                ]);
            } else {
                Log::info('Imagick extension not loaded, skipping PDF thumbnail generation', [
                    'file_id' => $this->file->id,
                ]);
            }
            
        } catch (\Exception $e) {
            Log::warning('PDF thumbnail generation error', [
                'file_id' => $this->file->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Extract PDF metadata
     */
    private function extractPdfMetadata(string $pdfPath): void
    {
        try {
            // PDF metadata çıkarma (sayfa sayısı, boyutlar, vb.)
            // Şimdilik sadece log'a yazıyoruz
            $fileSize = filesize($pdfPath);
            
            Log::info('PDF metadata extraction', [
                'file_id' => $this->file->id,
                'file_size' => $fileSize,
            ]);
            
            // Gelecekte PDF parser kütüphanesi eklenebilir (smalot/pdfparser gibi)
            
        } catch (\Exception $e) {
            Log::warning('PDF metadata extraction error', [
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
        Log::error('PDF processing job failed permanently', [
            'file_id' => $this->file->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
