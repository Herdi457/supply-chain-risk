<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\SupplyChainRiskController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\AdminController;
use App\Models\Port;
use App\Models\RiskScore;

// JALUR AUTHENTICATION
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// JALUR PROTECTED (Harus login dulu)
Route::middleware(['auth'])->group(function () {
    
    // Halaman utama dashboard peta
    Route::get('/', function () {
        try {
            // Use longer cache time and simpler queries
            $ports = Cache::remember('map_dashboard_ports_v2', 3600, function () {
                return Port::select('id', 'port_name', 'country_code', 'latitude', 'longitude', 'index_number')
                    ->limit(100)
                    ->get();
            });
            
            $risks = Cache::remember('map_dashboard_risks_v2', 1800, function () {
                return RiskScore::select('id', 'country_id', 'total_risk_score', 'risk_level', 'updated_at')
                    ->orderByDesc('updated_at')
                    ->limit(50)
                    ->get();
            });
            
            // Load country names separately to avoid N+1
            $countryIds = $risks->pluck('country_id')->unique();
            $countries = Cache::remember('countries_map_' . md5($countryIds->implode(',')), 3600, function () use ($countryIds) {
                return \App\Models\Country::whereIn('id', $countryIds)
                    ->select('id', 'code', 'name')
                    ->get()
                    ->keyBy('id');
            });
            
            // Attach country data manually
            $risks->each(function($risk) use ($countries) {
                $risk->country = $countries->get($risk->country_id);
            });
            
            \Log::info('Loading map dashboard', ['ports_count' => $ports->count(), 'risks_count' => $risks->count()]);
            return view('map_dashboard', compact('ports', 'risks'));
            
        } catch (\Exception $e) {
            \Log::error('Map dashboard error: ' . $e->getMessage());
            // Fallback to empty data rather than error
            return view('map_dashboard', ['ports' => collect([]), 'risks' => collect([])]);
        }
    });

    Route::get('/dashboard', function () {
        try {
            // Use longer cache time and simpler queries
            $ports = Cache::remember('map_dashboard_ports_v2', 3600, function () {
                return Port::select('id', 'port_name', 'country_code', 'latitude', 'longitude', 'index_number')
                    ->limit(100)
                    ->get();
            });
            
            $risks = Cache::remember('map_dashboard_risks_v2', 1800, function () {
                return RiskScore::select('id', 'country_id', 'total_risk_score', 'risk_level', 'updated_at')
                    ->orderByDesc('updated_at')
                    ->limit(50)
                    ->get();
            });
            
            // Load country names separately to avoid N+1
            $countryIds = $risks->pluck('country_id')->unique();
            $countries = Cache::remember('countries_map_' . md5($countryIds->implode(',')), 3600, function () use ($countryIds) {
                return \App\Models\Country::whereIn('id', $countryIds)
                    ->select('id', 'code', 'name')
                    ->get()
                    ->keyBy('id');
            });
            
            // Attach country data manually
            $risks->each(function($risk) use ($countries) {
                $risk->country = $countries->get($risk->country_id);
            });
            
            \Log::info('Loading dashboard', ['ports_count' => $ports->count(), 'risks_count' => $risks->count()]);
            return view('map_dashboard', compact('ports', 'risks'));
            
        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());
            // Fallback to empty data rather than error
            return view('map_dashboard', ['ports' => collect([]), 'risks' => collect([])]);
        }
    })->name('dashboard');

    // Dashboard pages
    Route::get('/country-dashboard', function () {
        return view('country_dashboard');
    });

    Route::get('/currency-dashboard', function () {
        return view('currency_dashboard');
    });

    Route::get('/news-dashboard', function () {
        return view('news_dashboard');
    });

    Route::get('/data-visualization', function () {
        return view('data_visualization');
    });

    Route::get('/country-comparison', function () {
        return view('country_comparison');
    });

    Route::get('/weather-monitoring', function () {
        return view('weather_monitoring', ['ports' => Port::all()]);
    });

    Route::get('/watchlist', [WatchlistController::class, 'index']);

    // Jalur pemicu hitung API Risiko Negara
    Route::get('/api/risk/{code}', [SupplyChainRiskController::class, 'calculateRisk']);
    Route::post('/api/risk/refresh-all', [SupplyChainRiskController::class, 'calculateAllRisks']);

    // REST API Endpoints untuk integrasi eksternal
    Route::prefix('api')->group(function () {
        Route::get('/countries', [ApiController::class, 'getCountries']);
        Route::get('/countries/{code}', [ApiController::class, 'getCountryDetail']);
        Route::get('/ports', [ApiController::class, 'getPorts']);
        Route::get('/ports/nearby', [ApiController::class, 'getNearbyPorts']);
        Route::get('/ports/stats', [ApiController::class, 'getPortStats']);
        Route::get('/news', [ApiController::class, 'getNews']);
        Route::get('/currency', [ApiController::class, 'getCurrency']);
        Route::get('/risk', [ApiController::class, 'getRiskScores']);
        
        // Watchlist API
        Route::get('/watchlist', [WatchlistController::class, 'getAll']);
        Route::post('/watchlist', [WatchlistController::class, 'store']);
        Route::put('/watchlist/{id}', [WatchlistController::class, 'update']);
        Route::delete('/watchlist/{id}', [WatchlistController::class, 'destroy']);
    });

    // Jalur cetak laporan PDF berdasarkan ID data risiko negara
    Route::get('/risk/print/{id}', function ($id) {
        $risk = RiskScore::with('country')->findOrFail($id);
        return view('print_report', compact('risk'));
    });

    // Jalur Panel Admin (CRUD Users, Ports, Articles) - Only for admin role
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index']);
        Route::post('/admin/users', [AdminController::class, 'storeUser']);
        Route::put('/admin/users/{id}', [AdminController::class, 'updateUser']);
        Route::delete('/admin/users/{id}', [AdminController::class, 'destroyUser']);
        
        Route::post('/admin/ports', [AdminController::class, 'storePort']);
        Route::put('/admin/ports/{id}', [AdminController::class, 'updatePort']);
        Route::delete('/admin/ports/{id}', [AdminController::class, 'destroyPort']);

        Route::post('/admin/articles', [AdminController::class, 'storeArticle']);
        Route::put('/admin/articles/{id}', [AdminController::class, 'updateArticle']);
        Route::delete('/admin/articles/{id}', [AdminController::class, 'destroyArticle']);
    });
});