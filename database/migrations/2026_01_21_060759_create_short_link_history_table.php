<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('short_link_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('short_link_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->text('original_url')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('action'); // created, updated, deleted
            $table->json('changes')->nullable(); // Değişikliklerin JSON formatı
            $table->timestamps();
            
            $table->index('short_link_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('short_link_histories');
    }
};
