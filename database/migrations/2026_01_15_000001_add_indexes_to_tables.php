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
        // QR Codes indexes
        if (Schema::hasTable('qr_codes')) {
            Schema::table('qr_codes', function (Blueprint $table) {
                if (!$this->hasIndex('qr_codes', 'qr_codes_token_index')) {
                    $table->index('token', 'qr_codes_token_index');
                }
                if (!$this->hasIndex('qr_codes', 'qr_codes_is_active_index')) {
                    $table->index('is_active', 'qr_codes_is_active_index');
                }
                if (!$this->hasIndex('qr_codes', 'qr_codes_user_id_index')) {
                    $table->index('user_id', 'qr_codes_user_id_index');
                }
            });
        }
        
        // Files indexes
        if (Schema::hasTable('files')) {
            Schema::table('files', function (Blueprint $table) {
                if (!$this->hasIndex('files', 'files_user_id_index')) {
                    $table->index('user_id', 'files_user_id_index');
                }
                if (!$this->hasIndex('files', 'files_category_id_index')) {
                    $table->index('category_id', 'files_category_id_index');
                }
            });
        }
        
        // vCards indexes
        if (Schema::hasTable('v_cards')) {
            Schema::table('v_cards', function (Blueprint $table) {
                if (!$this->hasIndex('v_cards', 'v_cards_token_index')) {
                    $table->index('token', 'v_cards_token_index');
                }
                if (!$this->hasIndex('v_cards', 'v_cards_user_id_index')) {
                    $table->index('user_id', 'v_cards_user_id_index');
                }
                if (!$this->hasIndex('v_cards', 'v_cards_is_active_index')) {
                    $table->index('is_active', 'v_cards_is_active_index');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('qr_codes')) {
            Schema::table('qr_codes', function (Blueprint $table) {
                $table->dropIndex('qr_codes_token_index');
                $table->dropIndex('qr_codes_is_active_index');
                $table->dropIndex('qr_codes_user_id_index');
            });
        }
        
        if (Schema::hasTable('files')) {
            Schema::table('files', function (Blueprint $table) {
                $table->dropIndex('files_user_id_index');
                $table->dropIndex('files_category_id_index');
            });
        }
        
        if (Schema::hasTable('v_cards')) {
            Schema::table('v_cards', function (Blueprint $table) {
                $table->dropIndex('v_cards_token_index');
                $table->dropIndex('v_cards_user_id_index');
                $table->dropIndex('v_cards_is_active_index');
            });
        }
    }
    
    /**
     * Check if index exists
     */
    private function hasIndex(string $table, string $index): bool
    {
        $connection = Schema::getConnection();
        $databaseName = $connection->getDatabaseName();
        
        $result = $connection->select(
            "SELECT COUNT(*) as count FROM information_schema.statistics 
             WHERE table_schema = ? AND table_name = ? AND index_name = ?",
            [$databaseName, $table, $index]
        );
        
        return $result[0]->count > 0;
    }
};
