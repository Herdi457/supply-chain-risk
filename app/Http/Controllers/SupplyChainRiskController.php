<?php

namespace App\Http\Controllers;

use App\Services\RiskCalculationService;

class SupplyChainRiskController extends Controller
{
    public function __construct(private RiskCalculationService $riskService)
    {
    }

    public function calculateRisk($code)
    {
        try {
            set_time_limit(300); // Increase execution time to 5 minutes for API calls
            $result = $this->riskService->calculateForCountry($code);

            return response()->json([
                'success' => true,
                'message' => 'Analisis Multi-API Real-Time Berhasil!',
                'data'    => $result,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }

    public function calculateAllRisks()
    {
        try {
            $results = $this->riskService->calculateAll();

            return response()->json([
                'success' => true,
                'message' => 'Semua indeks risiko berhasil diperbarui secara real-time.',
                'data'    => $results,
                'total'   => count($results),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
