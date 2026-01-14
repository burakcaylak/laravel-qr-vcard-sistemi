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
        Schema::create('v_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            
            // Turkish fields
            $table->string('name_tr')->nullable();
            $table->string('title_tr')->nullable();
            $table->string('phone_tr')->nullable();
            $table->string('email_tr')->nullable();
            $table->string('company_tr')->nullable();
            $table->text('address_tr')->nullable();
            $table->string('company_phone_tr')->nullable();
            $table->string('extension_tr')->nullable();
            $table->string('fax_tr')->nullable();
            $table->string('mobile_phone_tr')->nullable();
            $table->string('website_tr')->nullable();
            
            // English fields
            $table->string('name_en')->nullable();
            $table->string('title_en')->nullable();
            $table->string('phone_en')->nullable();
            $table->string('email_en')->nullable();
            $table->string('company_en')->nullable();
            $table->text('address_en')->nullable();
            $table->string('company_phone_en')->nullable();
            $table->string('extension_en')->nullable();
            $table->string('fax_en')->nullable();
            $table->string('mobile_phone_en')->nullable();
            $table->string('website_en')->nullable();
            
            // Common fields
            $table->string('email')->nullable(); // Email is same for both languages
            $table->string('phone')->nullable(); // Phone is same for both languages
            $table->string('mobile_phone')->nullable(); // Mobile phone is same for both languages
            $table->string('website')->nullable(); // Website is same for both languages
            
            // QR Code related
            $table->string('token')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->unsignedInteger('scan_count')->default(0);
            $table->string('file_path')->nullable();
            
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
        Schema::dropIfExists('v_cards');
    }
};
