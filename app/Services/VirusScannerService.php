<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class VirusScannerService
{
    /**
     * Scan file for viruses using ClamAV
     * 
     * @param string $filePath Full path to the file
     * @return array ['clean' => bool, 'message' => string]
     */
    public static function scan(string $filePath): array
    {
        // ClamAV yüklü mü kontrol et
        $clamavPath = config('files.clamav_path', '/usr/bin/clamscan');
        
        if (!file_exists($clamavPath)) {
            // ClamAV yüklü değilse, sadece log yaz ve geç
            Log::info('ClamAV not installed, skipping virus scan', [
                'file_path' => $filePath,
            ]);
            
            return [
                'clean' => true,
                'message' => 'Virus scanner not available',
            ];
        }
        
        // ClamAV ile tarama yap
        $command = escapeshellarg($clamavPath) . ' --no-summary ' . escapeshellarg($filePath);
        $output = [];
        $returnCode = 0;
        
        exec($command, $output, $returnCode);
        
        // ClamAV return code: 0 = clean, 1 = virus found
        if ($returnCode === 0) {
            return [
                'clean' => true,
                'message' => 'File is clean',
            ];
        } else {
            Log::warning('Virus detected in file', [
                'file_path' => $filePath,
                'output' => implode("\n", $output),
            ]);
            
            return [
                'clean' => false,
                'message' => 'Virus detected: ' . implode("\n", $output),
            ];
        }
    }
}
