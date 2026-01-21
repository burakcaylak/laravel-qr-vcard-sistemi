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
use App\Helpers\ActivityLogHelper;

class BulkDeleteFilesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600; // 10 dakika timeout
    public $tries = 2; // 2 kez deneme

    protected $fileIds;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(array $fileIds, int $userId)
    {
        $this->fileIds = $fileIds;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $deletedCount = 0;
            $failedCount = 0;
            
            foreach ($this->fileIds as $fileId) {
                try {
                    $file = File::find($fileId);
                    
                    if (!$file) {
                        Log::warning('File not found for bulk delete', [
                            'file_id' => $fileId,
                            'user_id' => $this->userId,
                        ]);
                        $failedCount++;
                        continue;
                    }
                    
                    // Kullanıcı kontrolü
                    if ($file->user_id !== $this->userId) {
                        Log::warning('User does not own file', [
                            'file_id' => $fileId,
                            'file_user_id' => $file->user_id,
                            'requested_user_id' => $this->userId,
                        ]);
                        $failedCount++;
                        continue;
                    }
                    
                    // Dosyayı disk'ten sil
                    if (Storage::disk('public')->exists($file->path)) {
                        Storage::disk('public')->delete($file->path);
                    }
                    
                    // Thumbnail varsa sil
                    $thumbnailPath = str_replace(
                        pathinfo($file->path, PATHINFO_DIRNAME),
                        pathinfo($file->path, PATHINFO_DIRNAME) . '/thumbnails',
                        $file->path
                    );
                    
                    if (Storage::disk('public')->exists($thumbnailPath)) {
                        Storage::disk('public')->delete($thumbnailPath);
                    }
                    
                    // Activity log
                    ActivityLogHelper::logFile('deleted', $file);
                    
                    // Model'i sil
                    $file->delete();
                    
                    $deletedCount++;
                    
                    Log::info('File deleted in bulk operation', [
                        'file_id' => $fileId,
                        'user_id' => $this->userId,
                    ]);
                    
                } catch (\Exception $e) {
                    Log::error('Error deleting file in bulk operation', [
                        'file_id' => $fileId,
                        'user_id' => $this->userId,
                        'error' => $e->getMessage(),
                    ]);
                    $failedCount++;
                }
            }
            
            Log::info('Bulk delete operation completed', [
                'user_id' => $this->userId,
                'total_files' => count($this->fileIds),
                'deleted_count' => $deletedCount,
                'failed_count' => $failedCount,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Bulk delete job failed', [
                'user_id' => $this->userId,
                'file_ids' => $this->fileIds,
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
        Log::error('Bulk delete job failed permanently', [
            'user_id' => $this->userId,
            'file_ids' => $this->fileIds,
            'error' => $exception->getMessage(),
        ]);
    }
}
