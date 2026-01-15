<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('brochures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('file_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('pdf_path'); // PDF dosyasının yolu
            
            // Arkaplan ayarları
            $table->enum('background_type', ['image', 'color'])->default('color');
            $table->string('background_image_path')->nullable(); // Arkaplan görseli
            $table->string('background_color')->default('#ffffff'); // Arkaplan rengi (hex)
            
            // QR kod ve erişim
            $table->string('token')->unique();
            $table->string('qr_code_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            
            // İstatistikler
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('download_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('token');
            $table->index('user_id');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brochures');
    }
};
