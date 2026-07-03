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
        Schema::create('risk_scores', function (Blueprint $table) {
            $table->id();
            // Menghubungkan tabel ini ke tabel countries (Foreign Key)
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            
            // Kolom-kolom nilai risiko tiap indikator (0.00 sampai 100.00)
            $table->decimal('weather_risk_score', 5, 2)->default(0);
            $table->decimal('inflation_risk_score', 5, 2)->default(0);
            $table->decimal('exchange_rate_risk_score', 5, 2)->default(0);
            $table->decimal('news_sentiment_risk_score', 5, 2)->default(0);
            
            // Total rata-rata tertimbang dan kategori tingkat risiko
            $table->decimal('total_risk_score', 5, 2)->default(0); 
            $table->string('risk_level'); // Isi: Low, Medium, atau High Risk
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_scores');
    }
};