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
        Schema::table('qr_code_file', function (Blueprint $table) {
            // Unique constraint'i kaldır - aynı dosya birden fazla kez eklenebilir
            // Constraint ismi Laravel tarafından otomatik oluşturulur: qr_code_file_qr_code_id_file_id_unique
            $table->dropUnique('qr_code_file_qr_code_id_file_id_unique');
        });
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
