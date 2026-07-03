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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique(); // Contoh: DE, CN, ID
            $table->string('name');              // Contoh: Germany, China, Indonesia
            $table->string('currency_code', 5)->nullable(); // Contoh: EUR, CNY, IDR
            $table->string('region')->nullable(); // Benua / Wilayah
            $table->string('languages')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};