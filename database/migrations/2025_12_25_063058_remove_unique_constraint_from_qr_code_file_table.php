<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Foreign key constraint'lerini geçici olarak kaldır
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        Schema::table('qr_code_file', function (Blueprint $table) {
            // Unique constraint'i kaldır - aynı dosya birden fazla kez eklenebilir
            // Constraint ismi Laravel tarafından otomatik oluşturulur: qr_code_file_qr_code_id_file_id_unique
            $table->dropUnique('qr_code_file_qr_code_id_file_id_unique');
        });
        
        // Foreign key constraint'lerini tekrar aktif et
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_code_file', function (Blueprint $table) {
            // Geri almak için unique constraint'i tekrar ekle
            $table->unique(['qr_code_id', 'file_id']);
        });
    }
};
