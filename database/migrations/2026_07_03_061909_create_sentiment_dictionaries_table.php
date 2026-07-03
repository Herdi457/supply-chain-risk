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
        Schema::create('sentiment_dictionaries', function (Blueprint $table) {
            $table->id();
            $table->string('word')->unique();               // Kata yang didaftarkan (Contoh: war, profit)
            $table->enum('type', ['positive', 'negative']); // Kategori kata (Hanya boleh diisi 'positive' atau 'negative')
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sentiment_dictionaries');
    }
};