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
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('file_id')->nullable()->constrained()->onDelete('set null');
            $table->string('token')->unique();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('requested_by')->nullable();
            $table->date('request_date')->nullable();
            $table->text('description')->nullable();
            $table->string('qr_type')->default('file');
            $table->text('content');
            $table->integer('size')->default(300);
            $table->string('format')->default('png');
            $table->string('file_path')->nullable();
            $table->unsignedInteger('scan_count')->default(0);
            $table->unsignedInteger('download_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('token');
            $table->index('file_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
