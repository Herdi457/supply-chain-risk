<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\RiskScore;
use App\Models\NewsCache;
use Illuminate\Support\Facades\Http;

class SupplyChainRiskController extends Controller
{
    /**
     * Mengambil data dari Komponen API Eksternal & Menghitung Weighted Risk Model
     */
    public function calculateRisk($countryCode)
    {
        // 1. Cari data negara berdasarkan kode (Contoh: ID, CN, US)
        $country = Country::where('code', strtoupper($countryCode))->first();
        
        if (!$country) {
            return response()->json(['error' => 'Negara tidak ditemukan di database.'], 404);
        }

        // ==========================================
        // SIMULASI KONEKSI INTEGRASI API EKSTERNAL
        // ==========================================
        
        // API 1 & 2: Weather & Storm Alert (Open-Meteo / NOAA)
        $weatherRisk = rand(15, 85); // Nilai dinamis risiko cuaca buruk

        // API 3: Inflation Rate Data (World Bank API)
        $inflationRisk = rand(10, 75); // Nilai risiko inflasi makro

        // API 4: Exchange Rates Currency (ExchangeRate-API)
        $exchangeRisk = rand(20, 80); // Nilai risiko volatilitas kurs mata uang

        // API 5, 6 & 7: News API & Sentiment Dictionary Lexicon
        $newsSentimentRisk = $this->analyzeNewsSentiment($country);

        // ==========================================
        // ALGORITMA UTAMA: WEIGHTED RISK SCORE MODEL
        // ==========================================
        // Bobot: 30% Cuaca + 25% Inflasi + 20% Kurs + 25% Sentimen Berita Geopolitik
        $totalRiskScore = ($weatherRisk * 0.30) + ($inflationRisk * 0.25) + ($exchangeRisk * 0.20) + ($newsSentimentRisk * 0.25);

        // Menentukan Kategori Kriteria Risiko
        if ($totalRiskScore < 40) {
            $riskLevel = 'Low Risk';
        } elseif ($totalRiskScore <= 70) {
            $riskLevel = 'Medium Risk';
        } else {
            $riskLevel = 'High Risk';
        }

        // 2. Simpan/Update Hasil Kalkulasi ke Database phpMyAdmin
        RiskScore::updateOrCreate(
            ['country_id' => $country->id],
            [
                'weather_risk_score' => $weatherRisk,
                'inflation_risk_score' => $inflationRisk,
                'exchange_rate_risk_score' => $exchangeRisk,
                'news_sentiment_risk_score' => $newsSentimentRisk,
                'total_risk_score' => $totalRiskScore,
                'risk_level' => $riskLevel,
                'updated_at' => now()
            ]
        );

        return response()->json([
            'status' => 'Success',
            'country' => $country->name,
            'metrics' => [
                'weather_and_storm_risk' => $weatherRisk . '%',
                'inflation_macro_risk' => $inflationRisk . '%',
                'exchange_rate_volatility_risk' => $exchangeRisk . '%',
                'geopolitical_news_sentiment_risk' => $newsSentimentRisk . '%',
            ],
            'final_result' => [
                'weighted_total_score' => round($totalRiskScore, 2) . '%',
                'conclusion_level' => $riskLevel
            ]
        ]);
    }

    /**
     * Fitur AI Lexicon-Based Sentiment Analysis
     */
    private function analyzeNewsSentiment($country)
    {
        $sampleTitles = [
            "Port operation in " . $country->name . " faces severe delay due to labor crisis",
            "Economic growth stability observed in " . $country->name . " trade channels",
            "New trade conflict and inflation risk warns global supply chain actors"
        ];

        $positiveMatches = 0;
        $negativeMatches = 0;

        // Ambil data kata dari kamus seeder di database
        $positiveWords = \DB::table('sentiment_dictionaries')->where('type', 'positive')->pluck('word')->toArray();
        $negativeWords = \DB::table('sentiment_dictionaries')->where('type', 'negative')->pluck('word')->toArray();

        foreach ($sampleTitles as $title) {
            $words = explode(' ', strtolower(preg_replace('/[^A-Za-z0-9 ]/', '', $title)));
            foreach ($words as $word) {
                if (in_array($word, $positiveWords)) $positiveMatches++;
                if (in_array($word, $negativeWords)) $negativeMatches++;
            }
        }

        $totalMatches = $positiveMatches + $negativeMatches;
        if ($totalMatches == 0) return 50.00;

        $sentimentRiskScore = ($negativeMatches / $totalMatches) * 100;

        // Simpan cache berita ke database
        NewsCache::create([
            'country_id' => $country->id,
            'title' => $sampleTitles[array_rand($sampleTitles)],
            'sentiment_result' => ($sentimentRiskScore > 50) ? 'Negative' : (($sentimentRiskScore < 50) ? 'Positive' : 'Neutral'),
            'positive_matches' => $positiveMatches,
            'negative_matches' => $negativeMatches,
            'published_at' => now()
        ]);

        return round($sentimentRiskScore, 2);
    }
}