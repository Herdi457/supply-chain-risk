<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplyChainRiskController;
use App\Http\Controllers\AuthController;
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
        $ports = Port::all();
        $risks = RiskScore::with('country')->get();
        return view('map_dashboard', compact('ports', 'risks'));
    });

    // Jalur pemicu hitung API Risiko Negara
    Route::get('/api/risk/{code}', [SupplyChainRiskController::class, 'calculateRisk']);

    // Jalur cetak laporan PDF berdasarkan ID data risiko negara
    Route::get('/risk/print/{id}', function ($id) {
        $risk = RiskScore::with('country')->findOrFail($id);
        return view('print_report', compact('risk'));
    });
});