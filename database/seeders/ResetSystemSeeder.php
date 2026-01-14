<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\QrCode;
use App\Models\File;
use App\Models\VCard;
use App\Models\VCardTemplate;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class ResetSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Production ortamında çalıştırmayı engelle
        if (app()->environment('production')) {
            $this->command->error('❌ Bu seeder production ortamında çalıştırılamaz!');
            $this->command->warn('⚠️  Güvenlik nedeniyle production\'da sistem sıfırlama işlemi yasaktır.');
            return;
        }

        $this->command->info('Sistem sıfırlanıyor...');

        // Foreign key constraint'leri geçici olarak devre dışı bırak (MySQL için)
        $driver = DB::connection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        }

        try {
            // 1. QR Kodları temizle
            $this->command->info('QR Kodları temizleniyor...');
            if (Schema::hasTable('qr_codes')) {
                QrCode::withTrashed()->forceDelete();
                // Storage'daki QR kod dosyalarını sil
                if (Storage::disk('public')->exists('qr-codes')) {
                    Storage::disk('public')->deleteDirectory('qr-codes');
                }
            }

            // 2. vCard'ları temizle
            $this->command->info('vCard\'lar temizleniyor...');
            if (Schema::hasTable('v_cards')) {
                VCard::withTrashed()->forceDelete();
                // Storage'daki vCard dosyalarını sil
                if (Storage::disk('public')->exists('v-cards')) {
                    Storage::disk('public')->deleteDirectory('v-cards');
                }
            }

            // 3. vCard Şablonlarını temizle
            $this->command->info('vCard Şablonları temizleniyor...');
            if (Schema::hasTable('v_card_templates')) {
                VCardTemplate::withTrashed()->forceDelete();
                // Storage'daki template dosyalarını sil
                if (Storage::disk('public')->exists('v-card-templates')) {
                    Storage::disk('public')->deleteDirectory('v-card-templates');
                }
            }

            // 4. Dosyaları (Media Library) temizle
            $this->command->info('Dosyalar (Media Library) temizleniyor...');
            if (Schema::hasTable('files')) {
                File::withTrashed()->forceDelete();
                // Storage'daki dosyaları sil
                if (Storage::disk('public')->exists('files')) {
                    Storage::disk('public')->deleteDirectory('files');
                }
                if (Storage::disk('public')->exists('thumbnails')) {
                    Storage::disk('public')->deleteDirectory('thumbnails');
                }
            }

            // 5. Activity Logs temizle
            $this->command->info('Activity Logs temizleniyor...');
            if (Schema::hasTable('activity_logs')) {
                DB::table('activity_logs')->truncate();
            }

            // 6. Addresses temizle
            $this->command->info('Adresler temizleniyor...');
            if (Schema::hasTable('addresses')) {
                DB::table('addresses')->truncate();
            }

            // 7. Sessions temizle
            $this->command->info('Sessions temizleniyor...');
            if (Schema::hasTable('sessions')) {
                DB::table('sessions')->truncate();
            }

            // 8. Jobs temizle (Queue)
            $this->command->info('Queue Jobs temizleniyor...');
            if (Schema::hasTable('jobs')) {
                DB::table('jobs')->truncate();
            }
            if (Schema::hasTable('failed_jobs')) {
                DB::table('failed_jobs')->truncate();
            }

            // 9. QR Code File ilişkilerini temizle
            $this->command->info('QR Code File ilişkileri temizleniyor...');
            if (Schema::hasTable('qr_code_file')) {
                DB::table('qr_code_file')->truncate();
            }

            // 10. Kategorileri temizle
            $this->command->info('Kategoriler temizleniyor...');
            if (Schema::hasTable('categories')) {
                Category::withTrashed()->forceDelete();
            }

            // 11. Tüm kullanıcıları temizle (default kullanıcı dahil)
            $this->command->info('Kullanıcılar temizleniyor...');
            if (Schema::hasTable('users')) {
                // Önce model_has_roles ve model_has_permissions tablolarından sil
                if (Schema::hasTable('model_has_roles')) {
                    DB::table('model_has_roles')
                        ->where('model_type', 'App\Models\User')
                        ->delete();
                }

                if (Schema::hasTable('model_has_permissions')) {
                    DB::table('model_has_permissions')
                        ->where('model_type', 'App\Models\User')
                        ->delete();
                }

                // Tüm kullanıcıları sil
                DB::table('users')->truncate();
            }

            // 12. AUTO_INCREMENT'leri sıfırla (ID'ler baştan başlasın)
            $this->command->info('AUTO_INCREMENT değerleri sıfırlanıyor...');
            $this->resetAutoIncrement($driver);

            // 13. Default kullanıcıyı oluştur (account_id = '0', ID = 1 olacak)
            $this->command->info('Default kullanıcı oluşturuluyor...');
            $defaultUser = User::create([
                'name' => 'Default User',
                'email' => 'admin@system.local',
                'password' => Hash::make('password'),
                'account_id' => '0',
                'language' => 'tr',
                'email_verified_at' => now(),
            ]);
            $this->command->info('Default kullanıcı oluşturuldu (ID: ' . $defaultUser->id . ').');

            // Default kullanıcıya superadmin rolü ver
            if (Schema::hasTable('roles')) {
                $superadminRole = DB::table('roles')->where('name', 'superadmin')->first();
                if ($superadminRole) {
                    // Mevcut rolleri temizle
                    DB::table('model_has_roles')
                        ->where('model_id', $defaultUser->id)
                        ->where('model_type', 'App\Models\User')
                        ->delete();
                    
                    // Superadmin rolünü ata
                    DB::table('model_has_roles')->insert([
                        'role_id' => $superadminRole->id,
                        'model_type' => 'App\Models\User',
                        'model_id' => $defaultUser->id,
                    ]);
                    $this->command->info('Default kullanıcıya superadmin rolü verildi.');
                }
            }

            // 14. Personal Access Tokens temizle
            $this->command->info('Personal Access Tokens temizleniyor...');
            if (Schema::hasTable('personal_access_tokens')) {
                DB::table('personal_access_tokens')->truncate();
            }

            $this->command->info('✅ Sistem başarıyla sıfırlandı!');
            $this->command->info('📧 Default kullanıcı bilgileri:');
            $this->command->info('   Email: admin@system.local');
            $this->command->info('   Şifre: password');
            $this->command->info('   Account ID: 0');
            $this->command->warn('⚠️  Lütfen ilk girişten sonra şifreyi değiştirin!');

        } catch (\Exception $e) {
            $this->command->error('Hata oluştu: ' . $e->getMessage());
            $this->command->error($e->getTraceAsString());
        } finally {
            // Foreign key constraint'leri tekrar aktif et
            if ($driver === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } elseif ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = ON;');
            }
        }
    }

    /**
     * Reset AUTO_INCREMENT values for all tables
     */
    protected function resetAutoIncrement(string $driver): void
    {
        $tables = [
            'users',
            'qr_codes',
            'files',
            'v_cards',
            'v_card_templates',
            'categories',
            'activity_logs',
            'addresses',
            'sessions',
            'jobs',
            'failed_jobs',
            'qr_code_file',
            'personal_access_tokens',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                try {
                    if ($driver === 'mysql') {
                        // MySQL için AUTO_INCREMENT'i 1'e sıfırla
                        DB::statement("ALTER TABLE `{$table}` AUTO_INCREMENT = 1");
                    } elseif ($driver === 'sqlite') {
                        // SQLite için sequence'i sıfırla
                        DB::statement("DELETE FROM sqlite_sequence WHERE name = '{$table}'");
                    }
                    $this->command->info("   ✓ {$table} AUTO_INCREMENT sıfırlandı");
                } catch (\Exception $e) {
                    $this->command->warn("   ⚠ {$table} AUTO_INCREMENT sıfırlanamadı: " . $e->getMessage());
                }
            }
        }
    }
}
