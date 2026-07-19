<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Port;
use App\Models\Country;
use App\Models\NewsCache;
use App\Models\RiskScore;

class ApiController extends Controller
{
    private static $fallbackEconomicData = [
        'ID' => ['gdp' => 1180000000000, 'inflation' => 3.2, 'exports' => 230000000000, 'imports' => 210000000000],
        'CN' => ['gdp' => 17700000000000, 'inflation' => 2.0, 'exports' => 3500000000000, 'imports' => 2700000000000],
        'US' => ['gdp' => 23300000000000, 'inflation' => 4.7, 'exports' => 2500000000000, 'imports' => 3400000000000],
        'DE' => ['gdp' => 4200000000000, 'inflation' => 7.9, 'exports' => 1600000000000, 'imports' => 1500000000000],
        'SG' => ['gdp' => 396000000000, 'inflation' => 6.1, 'exports' => 645000000000, 'imports' => 520000000000],
        'JP' => ['gdp' => 4900000000000, 'inflation' => 2.5, 'exports' => 750000000000, 'imports' => 810000000000],
        'NL' => ['gdp' => 1000000000000, 'inflation' => 10.0, 'exports' => 730000000000, 'imports' => 660000000000],
        'AU' => ['gdp' => 1550000000000, 'inflation' => 6.6, 'exports' => 340000000000, 'imports' => 280000000000],
        'BR' => ['gdp' => 1600000000000, 'inflation' => 9.3, 'exports' => 280000000000, 'imports' => 220000000000],
        'GB' => ['gdp' => 3100000000000, 'inflation' => 9.1, 'exports' => 860000000000, 'imports' => 900000000000],
        'IN' => ['gdp' => 3150000000000, 'inflation' => 6.7, 'exports' => 420000000000, 'imports' => 570000000000],
        'KR' => ['gdp' => 1800000000000, 'inflation' => 5.1, 'exports' => 640000000000, 'imports' => 610000000000],
        'ZA' => ['gdp' => 420000000000, 'inflation' => 6.9, 'exports' => 120000000000, 'imports' => 110000000000],
        'CA' => ['gdp' => 2000000000000, 'inflation' => 6.8, 'exports' => 500000000000, 'imports' => 490000000000],
        'MY' => ['gdp' => 370000000000, 'inflation' => 3.4, 'exports' => 300000000000, 'imports' => 240000000000],
        'TH' => ['gdp' => 505000000000, 'inflation' => 6.1, 'exports' => 270000000000, 'imports' => 250000000000],
        'SA' => ['gdp' => 830000000000, 'inflation' => 2.5, 'exports' => 260000000000, 'imports' => 150000000000],
        'AE' => ['gdp' => 410000000000, 'inflation' => 4.8, 'exports' => 320000000000, 'imports' => 260000000000],
        'EG' => ['gdp' => 400000000000, 'inflation' => 13.9, 'exports' => 40000000000, 'imports' => 75000000000],
        'FR' => ['gdp' => 2900000000000, 'inflation' => 5.2, 'exports' => 580000000000, 'imports' => 640000000000],
        'IT' => ['gdp' => 2100000000000, 'inflation' => 8.2, 'exports' => 610000000000, 'imports' => 560000000000],
        'ES' => ['gdp' => 1400000000000, 'inflation' => 8.4, 'exports' => 390000000000, 'imports' => 420000000000],
        'RU' => ['gdp' => 1780000000000, 'inflation' => 13.8, 'exports' => 490000000000, 'imports' => 240000000000],
        'MX' => ['gdp' => 1300000000000, 'inflation' => 7.9, 'exports' => 490000000000, 'imports' => 500000000000],
        'NZ' => ['gdp' => 250000000000, 'inflation' => 7.2, 'exports' => 50000000000, 'imports' => 52000000000],
        'KH' => ['gdp' => 27000000000, 'inflation' => 5.3, 'exports' => 17000000000, 'imports' => 22000000000],
        'PH' => ['gdp' => 394000000000, 'inflation' => 5.8, 'exports' => 74000000000, 'imports' => 126000000000],
        'VN' => ['gdp' => 366000000000, 'inflation' => 3.2, 'exports' => 336000000000, 'imports' => 330000000000],
        'PK' => ['gdp' => 348000000000, 'inflation' => 12.1, 'exports' => 31000000000, 'imports' => 56000000000],
        'TR' => ['gdp' => 815000000000, 'inflation' => 72.3, 'exports' => 225000000000, 'imports' => 290000000000],
        // Additional major economies
        'CH' => ['gdp' => 800000000000, 'inflation' => 2.8, 'exports' => 350000000000, 'imports' => 280000000000],
        'SE' => ['gdp' => 585000000000, 'inflation' => 8.4, 'exports' => 185000000000, 'imports' => 175000000000],
        'PL' => ['gdp' => 640000000000, 'inflation' => 14.4, 'exports' => 320000000000, 'imports' => 330000000000],
        'BE' => ['gdp' => 530000000000, 'inflation' => 10.3, 'exports' => 470000000000, 'imports' => 460000000000],
        'AR' => ['gdp' => 480000000000, 'inflation' => 94.8, 'exports' => 89000000000, 'imports' => 80000000000],
        'AT' => ['gdp' => 470000000000, 'inflation' => 8.6, 'exports' => 200000000000, 'imports' => 210000000000],
        'NO' => ['gdp' => 480000000000, 'inflation' => 5.8, 'exports' => 170000000000, 'imports' => 110000000000],
        'IE' => ['gdp' => 500000000000, 'inflation' => 7.8, 'exports' => 230000000000, 'imports' => 140000000000],
        'IL' => ['gdp' => 520000000000, 'inflation' => 4.4, 'exports' => 140000000000, 'imports' => 130000000000],
        'DK' => ['gdp' => 400000000000, 'inflation' => 7.7, 'exports' => 150000000000, 'imports' => 140000000000],
        'FI' => ['gdp' => 280000000000, 'inflation' => 7.1, 'exports' => 90000000000, 'imports' => 88000000000],
        'PT' => ['gdp' => 250000000000, 'inflation' => 7.8, 'exports' => 85000000000, 'imports' => 95000000000],
        'GR' => ['gdp' => 210000000000, 'inflation' => 9.3, 'exports' => 65000000000, 'imports' => 82000000000],
        'CZ' => ['gdp' => 280000000000, 'inflation' => 15.1, 'exports' => 220000000000, 'imports' => 210000000000],
        'RO' => ['gdp' => 280000000000, 'inflation' => 13.8, 'exports' => 100000000000, 'imports' => 120000000000],
        'CL' => ['gdp' => 300000000000, 'inflation' => 11.6, 'exports' => 95000000000, 'imports' => 88000000000],
        'CO' => ['gdp' => 310000000000, 'inflation' => 10.2, 'exports' => 50000000000, 'imports' => 60000000000],
        'BD' => ['gdp' => 410000000000, 'inflation' => 7.7, 'exports' => 47000000000, 'imports' => 82000000000],
        'NG' => ['gdp' => 440000000000, 'inflation' => 18.8, 'exports' => 60000000000, 'imports' => 70000000000],
        'HK' => ['gdp' => 360000000000, 'inflation' => 1.9, 'exports' => 670000000000, 'imports' => 680000000000],
        'TW' => ['gdp' => 790000000000, 'inflation' => 2.9, 'exports' => 450000000000, 'imports' => 410000000000],
    ];
    /**
     * GET /api/countries
     * Mendapatkan data negara dari REST Countries API dan World Bank API
     */
    public function getCountries(Request $request)
    {
        try {
            $search = $request->get('search');
            $limit = $request->get('limit', 300); // Increase default limit to show all countries

            // Use database countries directly
            \Log::info('Using database countries');
            $dbCountries = Country::all();
            $countries = $dbCountries->map(function($country) {
                return [
                    'name' => ['common' => $country->name, 'official' => $country->name],
                    'cca2' => $country->code,
                    'cca3' => $country->code,
                    'capital' => [''], // Not in database schema
                    'region' => $country->region ?? 'Unknown',
                    'subregion' => '', // Not in database schema
                    'population' => $country->population ?? 0,
                    'area' => $country->area ?? 0,
                    'currencies' => $country->currency_code ? [[$country->currency_code => $country->currency_code]] : [],
                    'languages' => $country->languages ? explode(',', $country->languages) : [],
                    'flags' => ['png' => 'https://flagcdn.com/w320/' . strtolower($country->code) . '.png'],
                    'latlng' => [0, 0] // Not in database schema
                ];
            })->toArray();

            // Filter jika ada search query
            if ($search) {
                $countries = array_filter($countries, function($country) use ($search) {
                    return stripos($country['name']['common'], $search) !== false;
                });
            }

            // Batasi hasil
            $countries = array_slice($countries, 0, $limit);

            // Format data
            $formattedCountries = [];
            foreach ($countries as $country) {
                if (!is_array($country)) continue;
                
                $formattedCountries[] = [
                    'name' => $country['name']['common'] ?? 'Unknown',
                    'official_name' => $country['name']['official'] ?? '',
                    'code' => $country['cca2'] ?? '',
                    'code3' => $country['cca3'] ?? '',
                    'capital' => is_array($country['capital'] ?? []) ? ($country['capital'][0] ?? '') : '',
                    'region' => $country['region'] ?? '',
                    'subregion' => $country['subregion'] ?? '',
                    'population' => $country['population'] ?? 0,
                    'area' => $country['area'] ?? 0,
                    'currencies' => $country['currencies'] ?? [],
                    'languages' => $country['languages'] ?? [],
                    'flag' => $country['flags']['png'] ?? '',
                    'lat' => is_array($country['latlng'] ?? []) ? ($country['latlng'][0] ?? 0) : 0,
                    'lng' => is_array($country['latlng'] ?? []) ? ($country['latlng'][1] ?? 0) : 0,
                ];
            }

            return response()->json([
                'success' => true,
                'data' => array_values($formattedCountries),
                'total' => count($formattedCountries)
            ]);

        } catch (\Exception $e) {
            \Log::error('Countries API error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching countries: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/countries/{code}
     * Mendapatkan detail negara tertentu dengan data ekonomi dari World Bank
     */
    public function getCountryDetail($code)
    {
        try {
            $cacheKey = "country_detail_{$code}";
            
            $cachedData = Cache::remember($cacheKey, 3600, function () use ($code) {
                // Get database country first as fallback
                $countryModel = Country::where('code', strtoupper($code))->first();
                
                // Get port for coordinates
                $port = Port::where('country_code', strtoupper($code))->first();
                
                // Try REST Countries API for additional data
                $restCountry = null;
                try {
                    $restCountriesResponse = Http::timeout(15)->get("https://restcountries.com/v3.1/alpha/" . strtoupper($code));
                    
                    if ($restCountriesResponse->successful()) {
                        $responseData = $restCountriesResponse->json();
                        
                        // Check if API returned deprecation error
                        if (!(isset($responseData['success']) && $responseData['success'] === false)) {
                            // Check if response is valid array
                            if (is_array($responseData) && !empty($responseData)) {
                                $restCountry = $responseData[0];
                            }
                        } else {
                            \Log::warning("REST Countries API deprecated for {$code}");
                        }
                    }
                } catch (\Exception $e) {
                    \Log::warning("REST Countries API exception for {$code}: " . $e->getMessage());
                }

                // Use database data if REST Countries API failed
                $countryName = $restCountry['name']['common'] ?? $countryModel?->name ?? 'Unknown';
                $countryOfficialName = $restCountry['name']['official'] ?? $countryModel?->name ?? 'Unknown';
                $countryCode = $restCountry['cca2'] ?? $code;
                $countryCode3 = $restCountry['cca3'] ?? $code;
                $capital = is_array($restCountry['capital'] ?? []) ? ($restCountry['capital'][0] ?? '') : '';
                $region = $restCountry['region'] ?? $countryModel?->region ?? '';
                $subregion = $restCountry['subregion'] ?? '';
                $population = $restCountry['population'] ?? $countryModel?->population ?? 0;
                $area = $restCountry['area'] ?? $countryModel?->area ?? 0;
                $flag = $restCountry['flags']['png'] ?? '';
                if (empty($flag)) {
                    $flag = 'https://flagcdn.com/w320/' . strtolower($countryCode) . '.png';
                }
                
                $lat = $port ? $port->latitude : $restCountry['latlng'][0] ?? 0;
                $lng = $port ? $port->longitude : $restCountry['latlng'][1] ?? 0;

                // Ambil data ekonomi dari World Bank API (cached separately)
                $economicData = $this->getWorldBankData($code);

                // Ambil data cuaca dari Open-Meteo (cached separately)
                $weatherData = null;
                if ($lat && $lng) {
                    $weatherCacheKey = "weather_{$lat}_{$lng}";
                    $weatherData = Cache::remember($weatherCacheKey, 600, function () use ($lat, $lng) {
                        $weatherResponse = Http::timeout(15)->get("https://api.open-meteo.com/v1/forecast", [
                            'latitude' => $lat,
                            'longitude' => $lng,
                            'current' => 'temperature_2m,relative_humidity_2m,precipitation,rain,wind_speed_10m,weather_code',
                            'timezone' => 'auto'
                        ]);

                        if ($weatherResponse->successful()) {
                            return $weatherResponse->json()['current'];
                        }
                        return null;
                    });
                }

                // Format currencies
                $currencies = [];
                if ($restCountry && isset($restCountry['currencies']) && is_array($restCountry['currencies'])) {
                    foreach ($restCountry['currencies'] as $currCode => $curr) {
                        $currencies[$currCode] = [
                            'name' => $curr['name'] ?? $currCode,
                            'symbol' => $curr['symbol'] ?? $currCode
                        ];
                    }
                } elseif ($countryModel && $countryModel->currency_code) {
                    $currencies[$countryModel->currency_code] = [
                        'name' => $countryModel->currency_code,
                        'symbol' => $countryModel->currency_code
                    ];
                }

                // Format languages
                $languages = [];
                if ($restCountry && isset($restCountry['languages']) && is_array($restCountry['languages'])) {
                    $languages = $restCountry['languages'];
                } elseif ($countryModel && $countryModel->languages) {
                    $langArray = explode(',', $countryModel->languages);
                    $languages = array_combine($langArray, $langArray);
                }

                return [
                    'basic_info' => [
                        'name' => $countryName,
                        'official_name' => $countryOfficialName,
                        'code' => $countryCode,
                        'code3' => $countryCode3,
                        'capital' => $capital,
                        'region' => $region,
                        'subregion' => $subregion,
                        'population' => $population,
                        'area' => $area,
                        'flag' => $flag,
                        'coordinates' => [$lat, $lng]
                    ],
                    'currencies' => $currencies,
                    'languages' => $languages,
                    'economic_data' => $economicData,
                    'weather' => $weatherData
                ];
            });

            if (!$cachedData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Country not found'
                ], 404);
            }

            // Get fresh risk score (not cached)
            $countryModel = Country::where('code', strtoupper($code))->first();
            $riskScore = null;
            if ($countryModel) {
                $riskScore = RiskScore::where('country_id', $countryModel->id)->first();
            }

            $cachedData['risk_score'] = $riskScore ? [
                'total_score' => $riskScore->total_risk_score,
                'level' => $riskScore->risk_level,
                'last_updated' => $riskScore->updated_at
            ] : null;

            return response()->json([
                'success' => true,
                'data' => $cachedData
            ]);

        } catch (\Exception $e) {
            \Log::error("Error fetching country detail for {$code}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching country detail: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/ports
     * Mendapatkan data pelabuhan
     */
    public function getPorts(Request $request)
    {
        try {
            // Cache ports data for 10 minutes
            $cacheKey = 'ports_list_' . md5(json_encode($request->all()));
            
            $result = Cache::remember($cacheKey, 600, function () use ($request) {
                $query = Port::query();

                // Filter berdasarkan negara
                if ($request->has('country')) {
                    $query->where('country_code', strtoupper($request->get('country')));
                }

                // Search berdasarkan nama pelabuhan
                if ($request->has('search')) {
                    $search = $request->get('search');
                    $query->where(function($q) use ($search) {
                        $q->where('port_name', 'like', "%{$search}%")
                          ->orWhere('country_code', 'like', "%{$search}%");
                    });
                }

                $limit = $request->get('limit', 50);
                $ports = $query->with('country:id,code,name')
                    ->select('id', 'port_name', 'country_code', 'latitude', 'longitude', 'index_number')
                    ->limit($limit)
                    ->get()
                    ->map(function ($port) {
                        return [
                            'id'           => $port->id,
                            'port_name'    => $port->port_name,
                            'country_code' => $port->country_code,
                            'country_name' => $port->country_name,
                            'latitude'     => $port->latitude,
                            'longitude'    => $port->longitude,
                            'index_number' => $port->index_number,
                        ];
                    });

                return [
                    'success' => true,
                    'data' => $ports,
                    'total' => $ports->count()
                ];
            });

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching ports: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/ports/stats
     * Get port statistics by region
     */
    public function getPortStats()
    {
        try {
            $cacheKey = 'ports_stats';
            
            $stats = Cache::remember($cacheKey, 3600, function () {
                $total = Port::count();
                
                // Get counts by region through country relationship
                $byRegion = Port::join('countries', 'ports.country_code', '=', 'countries.code')
                    ->selectRaw('countries.region, COUNT(*) as count')
                    ->groupBy('countries.region')
                    ->pluck('count', 'region')
                    ->toArray();
                
                return [
                    'total' => $total,
                    'by_region' => $byRegion
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $stats,
                'by_region' => $stats['by_region']
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Port stats error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching port stats'
            ], 500);
        }
    }

    /**
     * GET /api/ports/nearby
     * Mendapatkan pelabuhan terdekat berdasarkan koordinat
     */
    public function getNearbyPorts(Request $request)
    {
        try {
            $lat = $request->get('lat');
            $lng = $request->get('lng');
            $radius = $request->get('radius', 1000); // km
            $limit = $request->get('limit', 20);

            if (!$lat || !$lng) {
                return response()->json([
                    'success' => false,
                    'message' => 'Latitude and longitude are required'
                ], 400);
            }

            // Use Haversine formula for distance calculation
            $ports = Port::selectRaw("
                    id, port_name, country_code, latitude, longitude, index_number,
                    (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance
                ", [$lat, $lng, $lat])
                ->having('distance', '<', $radius)
                ->orderBy('distance')
                ->limit($limit)
                ->with('country:id,code,name')
                ->get()
                ->map(function ($port) {
                    return [
                        'id'           => $port->id,
                        'port_name'    => $port->port_name,
                        'country_code' => $port->country_code,
                        'country_name' => $port->country_name,
                        'latitude'     => $port->latitude,
                        'longitude'    => $port->longitude,
                        'index_number' => $port->index_number,
                        'distance'     => round($port->distance, 2)
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $ports,
                'total' => $ports->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching nearby ports: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/news
     * Mendapatkan berita dari GNews API
     */
    public function getNews(Request $request)
    {
        try {
            $apiKey = config('services.gnews.key');
            
            if (empty($apiKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'GNews API key not configured. Please set GNEWS_API_KEY in .env file'
                ], 500);
            }

            $topic = $request->get('topic', 'supply chain');
            $lang = $request->get('lang', 'en');
            $limit = $request->get('limit', 10);

            // Cache berita untuk 1 jam
            $cacheKey = "news_{$topic}_{$lang}_{$limit}";
            
            $news = Cache::remember($cacheKey, 3600, function () use ($apiKey, $topic, $lang, $limit) {
                try {
                    $response = Http::timeout(15)->get('https://gnews.io/api/v4/search', [
                        'q' => $topic,
                        'lang' => $lang,
                        'max' => $limit,
                        'apikey' => $apiKey
                    ]);

                    if ($response->successful()) {
                        return $response->json();
                    }
                } catch (\Exception $e) {
                    \Log::error('GNews API failed: ' . $e->getMessage());
                }
                return ['articles' => []];
            });

            // Simpan ke cache database
            foreach ($news['articles'] ?? [] as $article) {
                try {
                    NewsCache::updateOrCreate(
                        ['source_url' => $article['url']],
                        [
                            'country_id' => 1, // Default country_id for now
                            'title' => $article['title'],
                            'description' => $article['description'] ?? '',
                            'source_url' => $article['url'],
                            'sentiment_result' => 'Neutral',
                            'positive_matches' => 0,
                            'negative_matches' => 0,
                            'published_at' => $article['publishedAt'] ?? now(),
                        ]
                    );
                } catch (\Exception $e) {
                    \Log::error('Failed to cache news article: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'data' => $news['articles'] ?? [],
                'total' => count($news['articles'] ?? [])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching news: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/currency
     * Mendapatkan kurs mata uang dari ExchangeRate API
     */
    public function getCurrency(Request $request)
    {
        try {
            $apiKey = config('services.exchangerate.key');
            
            if (empty($apiKey)) {
                return $this->getCurrencyFallback($base, $target);
            }

            $base = strtoupper($request->get('base', 'USD'));
            $target = $request->get('target'); // Opsional: untuk currency spesifik

            // Cache rate untuk 1 jam
            $cacheKey = "currency_{$base}";
            
            $rates = Cache::remember($cacheKey, 3600, function () use ($apiKey, $base) {
                $response = Http::timeout(15)->get("https://v6.exchangerate-api.com/v6/{$apiKey}/latest/{$base}");

                if ($response->successful()) {
                    return $response->json();
                }
                return null;
            });

            if (!$rates || !isset($rates['conversion_rates'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch currency rates'
                ], 500);
            }

            // Jika ada target currency spesifik
            if ($target) {
                $targetUpper = strtoupper($target);
                $rate = $rates['conversion_rates'][$targetUpper] ?? null;

                return response()->json([
                    'success' => true,
                    'data' => [
                        'base' => $base,
                        'target' => $targetUpper,
                        'rate' => $rate,
                        'last_updated' => $rates['time_last_update_utc'] ?? null
                    ]
                ]);
            }

            // Return semua rates
            return response()->json([
                'success' => true,
                'data' => [
                    'base' => $base,
                    'rates' => $rates['conversion_rates'],
                    'last_updated' => $rates['time_last_update_utc'] ?? null
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching currency rates: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/risk
     * Mendapatkan semua risk scores
     */
    public function getRiskScores(Request $request)
    {
        try {
            $query = RiskScore::with('country');

            // Filter by risk level
            if ($request->has('level')) {
                $query->where('risk_level', $request->get('level'));
            }

            // Sort by updated_at (most recent first) to show newly calculated risks
            $sortOrder = $request->get('sort', 'desc');
            $sortBy = $request->get('by', 'updated_at');
            
            if ($sortBy === 'score') {
                $query->orderBy('total_risk_score', $sortOrder);
            } else {
                $query->orderBy('updated_at', $sortOrder);
            }

            $limit = $request->get('limit', 50);
            $risks = $query->limit($limit)->get();

            return response()->json([
                'success' => true,
                'data' => $risks,
                'total' => $risks->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching risk scores: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper: Mengambil data ekonomi dari World Bank API
     */
    private function getWorldBankData($countryCode)
    {
        $code = strtoupper($countryCode);
        try {
            $cacheKey = "worldbank_{$code}";
            
            $data = Cache::remember($cacheKey, 86400, function () use ($code) {
                $data = [];

                // GDP (NY.GDP.MKTP.CD) - Try current year and last 3 years
                try {
                    $currentYear = date('Y');
                    for ($yearOffset = 0; $yearOffset <= 3; $yearOffset++) {
                        $year = $currentYear - $yearOffset;
                        $gdpResponse = Http::timeout(5)->get("https://api.worldbank.org/v2/country/{$code}/indicator/NY.GDP.MKTP.CD", [
                            'format' => 'json',
                            'per_page' => 1,
                            'date' => $year
                        ]);
                        if ($gdpResponse->successful() && isset($gdpResponse->json()[1][0]['value'])) {
                            $data['gdp'] = $gdpResponse->json()[1][0]['value'];
                            break;
                        }
                    }
                } catch (\Exception $e) {
                    \Log::info("World Bank GDP failed for {$code}: " . $e->getMessage());
                }

                // Inflation (FP.CPI.TOTL.ZG)
                try {
                    $currentYear = date('Y');
                    for ($yearOffset = 0; $yearOffset <= 3; $yearOffset++) {
                        $year = $currentYear - $yearOffset;
                        $inflationResponse = Http::timeout(5)->get("https://api.worldbank.org/v2/country/{$code}/indicator/FP.CPI.TOTL.ZG", [
                            'format' => 'json',
                            'per_page' => 1,
                            'date' => $year
                        ]);
                        if ($inflationResponse->successful() && isset($inflationResponse->json()[1][0]['value'])) {
                            $data['inflation'] = $inflationResponse->json()[1][0]['value'];
                            break;
                        }
                    }
                } catch (\Exception $e) {
                    \Log::info("World Bank Inflation failed for {$code}: " . $e->getMessage());
                }

                // Exports (NE.EXP.GNFS.CD)
                try {
                    $currentYear = date('Y');
                    for ($yearOffset = 0; $yearOffset <= 3; $yearOffset++) {
                        $year = $currentYear - $yearOffset;
                        $exportsResponse = Http::timeout(5)->get("https://api.worldbank.org/v2/country/{$code}/indicator/NE.EXP.GNFS.CD", [
                            'format' => 'json',
                            'per_page' => 1,
                            'date' => $year
                        ]);
                        if ($exportsResponse->successful() && isset($exportsResponse->json()[1][0]['value'])) {
                            $data['exports'] = $exportsResponse->json()[1][0]['value'];
                            break;
                        }
                    }
                } catch (\Exception $e) {
                    \Log::info("World Bank Exports failed for {$code}: " . $e->getMessage());
                }

                // Imports (NE.IMP.GNFS.CD)
                try {
                    $currentYear = date('Y');
                    for ($yearOffset = 0; $yearOffset <= 3; $yearOffset++) {
                        $year = $currentYear - $yearOffset;
                        $importsResponse = Http::timeout(5)->get("https://api.worldbank.org/v2/country/{$code}/indicator/NE.IMP.GNFS.CD", [
                            'format' => 'json',
                            'per_page' => 1,
                            'date' => $year
                        ]);
                        if ($importsResponse->successful() && isset($importsResponse->json()[1][0]['value'])) {
                            $data['imports'] = $importsResponse->json()[1][0]['value'];
                            break;
                        }
                    }
                } catch (\Exception $e) {
                    \Log::info("World Bank Imports failed for {$code}: " . $e->getMessage());
                }

                return $data;
            });

            // Merge with local fallback if any fields are missing
            if (isset(self::$fallbackEconomicData[$code])) {
                $fallback = self::$fallbackEconomicData[$code];
                $data['gdp'] = $data['gdp'] ?? $fallback['gdp'];
                $data['inflation'] = $data['inflation'] ?? $fallback['inflation'];
                $data['exports'] = $data['exports'] ?? $fallback['exports'];
                $data['imports'] = $data['imports'] ?? $fallback['imports'];
            } else {
                // Generate intelligent fallback based on country population/region
                $country = Country::where('code', $code)->first();
                if ($country && empty($data)) {
                    $data = $this->generateEconomicEstimate($country);
                }
            }

            return $data;

        } catch (\Exception $e) {
            \Log::error("World Bank API error for {$code}: " . $e->getMessage());
            
            // Try fallback data
            if (isset(self::$fallbackEconomicData[$code])) {
                return self::$fallbackEconomicData[$code];
            }
            
            // Generate estimate as last resort
            $country = Country::where('code', $code)->first();
            if ($country) {
                return $this->generateEconomicEstimate($country);
            }
            
            return [
                'gdp' => null,
                'inflation' => null,
                'exports' => null,
                'imports' => null
            ];
        }
    }

    /**
     * Generate realistic economic estimates based on country characteristics
     */
    private function generateEconomicEstimate($country)
    {
        $population = $country->population ?? 10000000;
        $region = $country->region ?? 'Unknown';
        
        // GDP per capita estimates by region (in USD)
        $gdpPerCapitaByRegion = [
            'Europe' => 35000,
            'Americas' => 25000,
            'Asia' => 15000,
            'Oceania' => 40000,
            'Africa' => 5000,
        ];
        
        // Inflation rate estimates by region (%)
        $inflationByRegion = [
            'Europe' => 6.5,
            'Americas' => 8.0,
            'Asia' => 5.0,
            'Oceania' => 6.0,
            'Africa' => 12.0,
        ];
        
        $gdpPerCapita = $gdpPerCapitaByRegion[$region] ?? 15000;
        $inflationRate = $inflationByRegion[$region] ?? 7.0;
        
        $estimatedGdp = $population * $gdpPerCapita;
        $estimatedExports = $estimatedGdp * 0.25; // 25% of GDP
        $estimatedImports = $estimatedGdp * 0.27; // 27% of GDP
        
        return [
            'gdp' => round($estimatedGdp),
            'inflation' => round($inflationRate + (rand(-20, 20) / 10), 1), // Add some variance
            'exports' => round($estimatedExports),
            'imports' => round($estimatedImports),
            'estimated' => true // Flag to indicate this is estimated data
        ];
    }

    private function getCurrencyFallback(string $base, ?string $target = null)
    {
        $cacheKey = "currency_fallback_{$base}";

        $rates = Cache::remember($cacheKey, 3600, function () use ($base) {
            $response = Http::timeout(15)->get("https://open.er-api.com/v6/latest/{$base}");
            if ($response->successful()) {
                return $response->json();
            }
            return null;
        });

        if (!$rates || !isset($rates['rates'])) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch currency rates from fallback API'
            ], 500);
        }

        if ($target) {
            $targetUpper = strtoupper($target);
            return response()->json([
                'success' => true,
                'data' => [
                    'base'         => strtoupper($base),
                    'target'       => $targetUpper,
                    'rate'         => $rates['rates'][$targetUpper] ?? null,
                    'last_updated' => $rates['time_last_update_utc'] ?? null,
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'base'         => strtoupper($base),
                'rates'        => $rates['rates'],
                'last_updated' => $rates['time_last_update_utc'] ?? null,
            ]
        ]);
    }
}
