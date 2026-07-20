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
        Schema::table('watchlists', function (Blueprint $table) {
            // Tambah kolom country_code dan country_name untuk kemudahan akses
            $table->string('country_code', 3)->after('country_id')->nullable();
            $table->string('country_name')->after('country_code')->nullable();
            $table->text('notes')->after('country_name')->nullable();
            
            // Index untuk query cepat
            $table->index(['user_id', 'country_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('watchlists', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'country_code']);
            $table->dropColumn(['country_code', 'country_name', 'notes']);
        });
    }
};
