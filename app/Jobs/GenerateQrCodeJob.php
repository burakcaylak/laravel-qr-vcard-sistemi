<?php

namespace App\Jobs;

use App\Models\QrCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateQrCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $qrCode;

    /**
     * Create a new job instance.
     */
    public function __construct(QrCode $qrCode)
    {
        $this->qrCode = $qrCode;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // QR kod oluşturma işlemi
            $this->qrCode->generateQrImage();
            
            Log::info('QR code generated successfully', [
                'user_id' => $this->qrCode->user_id,
                'qr_code_id' => $this->qrCode->id,
                'type' => $this->qrCode->qr_type,
            ]);
        } catch (\Exception $e) {
            Log::error('QR code generation failed', [
                'qr_code_id' => $this->qrCode->id,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }
}
