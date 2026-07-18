<?php

namespace App\Services;

use App\Models\Country;
use App\Models\Port;
use App\Models\RiskScore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class RiskCalculationService
{
    /** Bobot sesuai spesifikasi PROJECT FINAL */
    private const WEIGHTS = [
        'weather'   => 0.30,
        'inflation' => 0.20,
        'news'      => 0.40,
        'currency'  => 0.10,
    ];

    public function calculateForCountry(string $countryCode): array
    {
        $code = strtoupper($countryCode);
        $port = Port::where('country_code', $code)->first();
        $country = Country::where('code', $code)->first();

        if (!$port) {
            throw new \RuntimeException("Data pelabuhan untuk negara {$code} tidak ditemukan.");
        }

        $weatherScore = $this->calculateWeatherRisk($port->latitude, $port->longitude);
        $inflationScore = $this->calculateInflationRisk($code);
        $newsScore = $this->calculateNewsRisk($code, $country?->name ?? $code);
        $currencyScore = $this->calculateCurrencyRisk($country?->currency_code ?? 'USD');

        $totalRiskScore = round(
            ($weatherScore * self::WEIGHTS['weather']) +
            ($inflationScore * self::WEIGHTS['inflation']) +
            ($newsScore * self::WEIGHTS['news']) +
            ($currencyScore * self::WEIGHTS['currency']),
            1
        );
        $totalRiskScore = min($totalRiskScore, 100);

        $riskLevel = $this->determineRiskLevel($totalRiskScore);
        $businessDecision = $this->getBusinessRecommendation($riskLevel);

        if ($country) {
            RiskScore::updateOrCreate(
                ['country_id' => $country->id],
                [
                    'weather_risk_score'          => $weatherScore,
                    'inflation_risk_score'        => $inflationScore,
                    'news_sentiment_risk_score'   => $newsScore,
                    'exchange_rate_risk_score'    => $currencyScore,
                    'total_risk_score'            => $totalRiskScore,
                    'risk_level'                  => $riskLevel,
                ]
            );
        }

        return [
            'country_code' => $code,
            'country_name' => $country?->name ?? $code,
            'total_score'  => $totalRiskScore,
            'level'        => $riskLevel,
            'components'   => [
                'weather'   => $weatherScore,
                'inflation' => $inflationScore,
                'news'      => $newsScore,
                'currency'  => $currencyScore,
            ],
            'weights'      => self::WEIGHTS,
            'keputusan'    => $businessDecision,
            'updated_at'   => now()->toIso8601String(),
        ];
    }

    public function calculateAll(array $countryCodes = null): array
    {
        $codes = $countryCodes ?? Port::distinct()->pluck('country_code')->toArray();
        $results = [];

        foreach ($codes as $code) {
            try {
                $results[] = $this->calculateForCountry($code);
            } catch (\Exception $e) {
                $results[] = [
                    'country_code' => strtoupper($code),
                    'success'      => false,
                    'message'      => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    private function calculateWeatherRisk(float $lat, float $lng): float
    {
        $score = 20;

        try {
            $response = Http::timeout(30)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude'  => $lat,
                'longitude' => $lng,
                'current'   => 'temperature_2m,rain,wind_speed_10m,weather_code',
                'timezone'  => 'auto',
            ]);

            if (!$response->successful()) {
                \Log::warning("Open-Meteo API failed for [{$lat}, {$lng}]: " . $response->status());
                return 50;
            }

            $current = $response->json()['current'] ?? [];
            $rain = $current['rain'] ?? 0;
            $wind = $current['wind_speed_10m'] ?? 0;
            $code = $current['weather_code'] ?? 0;

            if ($rain > 10) {
                $score += 35;
            } elseif ($rain > 5) {
                $score += 25;
            } elseif ($rain > 0) {
                $score += 10;
            }

            if ($wind > 50) {
                $score += 40;
            } elseif ($wind > 30) {
                $score += 25;
            } elseif ($wind > 20) {
                $score += 10;
            }

            if (in_array($code, [95, 96, 99])) {
                $score += 35;
            }

            return min($score, 100);
        } catch (\Exception $e) {
            \Log::error("Open-Meteo API exception for [{$lat}, {$lng}]: " . $e->getMessage());
            return 50;
        }
    }

    private function calculateInflationRisk(string $countryCode): float
    {
        $cacheKey = "inflation_risk_{$countryCode}";

        return Cache::remember($cacheKey, 3600, function () use ($countryCode) {
            try {
                $response = Http::timeout(30)->get(
                    "https://api.worldbank.org/v2/country/{$countryCode}/indicator/FP.CPI.TOTL.ZG",
                    ['format' => 'json', 'per_page' => 1, 'MRV' => 1]
                );

                if (!$response->successful()) {
                    \Log::warning("World Bank API failed for {$countryCode}: " . $response->status());
                    return 50;
                }

                $data = $response->json();
                if (!isset($data[1]) || !isset($data[1][0]) || !isset($data[1][0]['value'])) {
                    \Log::warning("World Bank API invalid data structure for {$countryCode}");
                    return 50;
                }

                $value = $data[1][0]['value'];
                if ($value === null) {
                    return 50;
                }

                if ($value > 15) return 90;
                if ($value > 10) return 75;
                if ($value > 7)  return 60;
                if ($value > 4)  return 45;
                if ($value > 2)  return 30;

                return 20;
            } catch (\Exception $e) {
                \Log::error("World Bank API exception for {$countryCode}: " . $e->getMessage());
                return 50;
            }
        });
    }

    private function calculateNewsRisk(string $countryCode, string $countryName): float
    {
        $positiveWords = DB::table('sentiment_dictionaries')->where('type', 'positive')->pluck('word')->toArray();
        $negativeWords = DB::table('sentiment_dictionaries')->where('type', 'negative')->pluck('word')->toArray();

        $articles = $this->fetchNewsArticles($countryName);
        if (empty($articles)) {
            return 50;
        }

        $positiveCount = 0;
        $negativeCount = 0;

        foreach ($articles as $article) {
            $text = strtolower(($article['title'] ?? '') . ' ' . ($article['description'] ?? ''));
            $words = preg_split('/\W+/', $text);

            foreach ($words as $word) {
                if (in_array($word, $positiveWords)) $positiveCount++;
                if (in_array($word, $negativeWords)) $negativeCount++;
            }
        }

        $total = $positiveCount + $negativeCount;
        if ($total === 0) {
            return 50;
        }

        $negativeRatio = $negativeCount / $total;
        return min(round(20 + ($negativeRatio * 80), 1), 100);
    }

    private function fetchNewsArticles(string $query): array
    {
        $apiKey = config('services.gnews.key');

        if (empty($apiKey)) {
            return [];
        }

        $cacheKey = 'news_risk_' . md5($query);

        return Cache::remember($cacheKey, 1800, function () use ($apiKey, $query) {
            $response = Http::timeout(15)->get('https://gnews.io/api/v4/search', [
                'q'      => "{$query} logistics trade economy",
                'lang'   => 'en',
                'max'    => 5,
                'apikey' => $apiKey,
            ]);

            if ($response->successful()) {
                return $response->json()['articles'] ?? [];
            }

            return [];
        });
    }

    private function calculateCurrencyRisk(string $currencyCode): float
    {
        $currencyCode = strtoupper($currencyCode);
        if ($currencyCode === 'USD') {
            return 20;
        }

        $apiKey = config('services.exchangerate.key');

        if (empty($apiKey)) {
            return $this->calculateCurrencyRiskFallback($currencyCode);
        }

        $cacheKey = "currency_risk_{$currencyCode}";

        return Cache::remember($cacheKey, 3600, function () use ($apiKey, $currencyCode) {
            try {
                $response = Http::timeout(30)->get(
                    "https://v6.exchangerate-api.com/v6/{$apiKey}/pair/USD/{$currencyCode}"
                );

                if (!$response->successful()) {
                    \Log::warning("ExchangeRate API failed for {$currencyCode}: " . $response->status());
                    return 50;
                }

                $rate = $response->json()['conversion_rate'] ?? null;
                if (!$rate) {
                    \Log::warning("ExchangeRate API invalid data for {$currencyCode}");
                    return 50;
                }

                // Semakin volatil/deviasi dari baseline, semakin tinggi risiko
                $deviation = abs(log($rate));
                if ($deviation > 8)  return 85;
                if ($deviation > 6)  return 70;
                if ($deviation > 4)  return 55;
                if ($deviation > 2)  return 40;

                return 25;
            } catch (\Exception $e) {
                \Log::error("ExchangeRate API exception for {$currencyCode}: " . $e->getMessage());
                return 50;
            }
        });
    }

    private function calculateCurrencyRiskFallback(string $currencyCode): float
    {
        $response = Http::timeout(10)->get("https://open.er-api.com/v6/latest/USD");

        if (!$response->successful()) {
            return 50;
        }

        $rate = $response->json()['rates'][$currencyCode] ?? null;
        if (!$rate) {
            return 50;
        }

        $deviation = abs(log($rate));
        return min(round(20 + ($deviation * 8), 1), 100);
    }

    private function determineRiskLevel(float $score): string
    {
        if ($score >= 70) return 'High Risk';
        if ($score >= 55) return 'Medium Risk';
        return 'Low Risk';
    }

    private function getBusinessRecommendation(string $level): string
    {
        return match ($level) {
            'High Risk'   => '⚠️ PERINGATAN BISNIS: Rute logistik sangat berbahaya. Disarankan menunda pengiriman atau mencari jalur alternatif.',
            'Medium Risk' => '⚡ REKOMENDASI BISNIS: Kondisi siaga. Pengiriman dapat dilanjutkan dengan pengawasan ketat dan asuransi tambahan.',
            default       => '✅ REKOMENDASI BISNIS: Jalur aman. Rantai pasok berjalan optimal, maksimalkan kuota pengiriman saat ini.',
        };
    }
}
