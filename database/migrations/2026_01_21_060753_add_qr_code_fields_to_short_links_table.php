<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('short_links', function (Blueprint $table) {
            $table->string('qr_code_path')->nullable()->after('expires_at');
            $table->integer('qr_code_size')->default(300)->after('qr_code_path');
            $table->string('qr_code_format')->default('png')->after('qr_code_size');
        });
    }

    public function down(): void
    {
        Schema::table('short_links', function (Blueprint $table) {
            $table->dropColumn(['qr_code_path', 'qr_code_size', 'qr_code_format']);
        });
    }
};
