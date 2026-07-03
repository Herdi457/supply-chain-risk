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
        Schema::create('news_cache', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke negara terkait (Foreign Key)
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            
            // Informasi dasar artikel berita
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('source_url')->nullable();
            
            // Hasil dari Lexicon Sentiment Analysis
            $table->string('sentiment_result')->default('Neutral'); // Positive, Neutral, Negative
            $table->integer('positive_matches')->default(0);        // Jumlah kata positif yang cocok
            $table->integer('negative_matches')->default(0);        // Jumlah kata negatif yang cocok
            
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_cache');
    }
};