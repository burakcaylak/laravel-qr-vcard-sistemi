<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Bu migration artık gereksiz çünkü önceki migration (063058) zaten unique constraint'i kaldırdı
        // Sadece foreign key constraint'lerinin doğru olduğundan emin ol
        try {
            // Eğer unique index hala varsa kaldır (önceki migration başarısız olduysa)
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::statement('ALTER TABLE qr_code_file DROP INDEX IF EXISTS qr_code_file_qr_code_id_file_id_unique');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } catch (\Exception $e) {
            // Index zaten yoksa veya başka bir hata varsa devam et
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Foreign key constraint'lerini geçici olarak kaldır
        DB::statement('ALTER TABLE qr_code_file DROP FOREIGN KEY qr_code_file_qr_code_id_foreign');
        DB::statement('ALTER TABLE qr_code_file DROP FOREIGN KEY qr_code_file_file_id_foreign');
        
        // Unique constraint'i tekrar ekle
        DB::statement('ALTER TABLE qr_code_file ADD UNIQUE KEY qr_code_file_qr_code_id_file_id_unique (qr_code_id, file_id)');
        
        // Foreign key constraint'lerini tekrar ekle
        DB::statement('ALTER TABLE qr_code_file ADD CONSTRAINT qr_code_file_qr_code_id_foreign FOREIGN KEY (qr_code_id) REFERENCES qr_codes(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE qr_code_file ADD CONSTRAINT qr_code_file_file_id_foreign FOREIGN KEY (file_id) REFERENCES files(id) ON DELETE CASCADE');
    }
};
