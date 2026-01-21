<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CleanProductionData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'production:clean 
                            {--force : Force clean without confirmation}
                            {--logs : Clean log files}
                            {--cache : Clean cache files}
                            {--storage : Clean storage files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean production data: logs, cache, and storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('Production verilerini temizlemek istediğinizden emin misiniz?', true)) {
            $this->info('İşlem iptal edildi.');
            return 0;
        }

        $this->info('Production temizleme işlemi başlatılıyor...');

        // Log temizleme
        if ($this->option('logs') || $this->option('force')) {
            $this->cleanLogs();
        }

        // Cache temizleme
        if ($this->option('cache') || $this->option('force')) {
            $this->cleanCache();
        }

        // Storage temizleme (sadece gereksiz dosyalar)
        if ($this->option('storage') || $this->option('force')) {
            $this->cleanStorage();
        }

        $this->info('✅ Production temizleme işlemi tamamlandı!');
        return 0;
    }

    /**
     * Clean log files
     */
    protected function cleanLogs()
    {
        $this->info('📝 Log dosyaları temizleniyor...');
        
        $logPath = storage_path('logs');
        $files = File::files($logPath);
        
        $deleted = 0;
        foreach ($files as $file) {
            if ($file->getExtension() === 'log') {
                File::delete($file->getPathname());
                $deleted++;
            }
        }
        
        $this->info("✅ {$deleted} log dosyası silindi.");
    }

    /**
     * Clean cache files
     */
    protected function cleanCache()
    {
        $this->info('💾 Cache dosyaları temizleniyor...');
        
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('view:clear');
        
        // Bootstrap cache
        $bootstrapCache = base_path('bootstrap/cache');
        if (File::exists($bootstrapCache)) {
            $files = File::files($bootstrapCache);
            foreach ($files as $file) {
                if ($file->getExtension() === 'php' && $file->getFilename() !== '.gitignore') {
                    File::delete($file->getPathname());
                }
            }
        }
        
        $this->info('✅ Cache dosyaları temizlendi.');
    }

    /**
     * Clean storage files (only unnecessary files)
     */
    protected function cleanStorage()
    {
        $this->info('📦 Storage dosyaları kontrol ediliyor...');
        
        // Framework cache
        $frameworkCache = storage_path('framework/cache');
        if (File::exists($frameworkCache)) {
            $this->cleanDirectory($frameworkCache);
        }
        
        // Framework sessions (sadece eski session'lar)
        $frameworkSessions = storage_path('framework/sessions');
        if (File::exists($frameworkSessions)) {
            $this->cleanOldSessions($frameworkSessions);
        }
        
        // Framework views
        $frameworkViews = storage_path('framework/views');
        if (File::exists($frameworkViews)) {
            $this->cleanDirectory($frameworkViews);
        }
        
        // Queue failed jobs (eğer varsa)
        $this->call('queue:flush');
        
        $this->info('✅ Storage temizleme tamamlandı.');
    }

    /**
     * Clean directory (keep .gitignore)
     */
    protected function cleanDirectory($path)
    {
        $files = File::files($path);
        foreach ($files as $file) {
            if ($file->getFilename() !== '.gitignore') {
                File::delete($file->getPathname());
            }
        }
    }

    /**
     * Clean old session files (older than 24 hours)
     */
    protected function cleanOldSessions($path)
    {
        $files = File::files($path);
        $deleted = 0;
        $cutoff = now()->subHours(24)->timestamp;
        
        foreach ($files as $file) {
            if ($file->getFilename() !== '.gitignore' && $file->getMTime() < $cutoff) {
                File::delete($file->getPathname());
                $deleted++;
            }
        }
        
        if ($deleted > 0) {
            $this->info("✅ {$deleted} eski session dosyası silindi.");
        }
    }
}
