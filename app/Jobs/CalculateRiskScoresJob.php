<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use App\Services\RiskCalculationService;
use App\Models\Port;

class CalculateRiskScoresJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 3600; // 1 hour timeout
    public $tries = 3; // Retry 3 times on failure

    /**
     * Execute the job.
     */
    public function handle(RiskCalculationService $riskService): void
    {
        Log::info('🚀 Starting automatic risk score calculation for all countries...');
        
        $startTime = now();
        
        try {
            // Get unique country codes that have ports
            $countryCodes = Port::distinct()
                ->pluck('country_code')
                ->filter()
                ->toArray();
            
            $totalCountries = count($countryCodes);
            $successful = 0;
            $failed = 0;
            
            Log::info("Found {$totalCountries} countries with ports to calculate");
            
            foreach ($countryCodes as $index => $code) {
                try {
                    $riskService->calculateForCountry($code);
                    $successful++;
                    
                    // Log progress every 10 countries
                    if (($index + 1) % 10 === 0) {
                        Log::info("Progress: " . ($index + 1) . "/{$totalCountries} countries processed");
                    }
                    
                    // Small delay to avoid API rate limits
                    usleep(500000); // 0.5 second delay
                    
                } catch (\Exception $e) {
                    $failed++;
                    Log::error("Failed to calculate risk for {$code}: " . $e->getMessage());
                }
            }
            
            $duration = now()->diffInSeconds($startTime);
            
            Log::info("✅ Risk score calculation completed!");
            Log::info("Total: {$totalCountries} | Success: {$successful} | Failed: {$failed}");
            Log::info("Duration: {$duration} seconds");
            
        } catch (\Exception $e) {
            Log::error('❌ Risk score calculation job failed: ' . $e->getMessage());
            throw $e; // Re-throw to trigger retry mechanism
        }
    }
    
    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('❌ CalculateRiskScoresJob failed after all retries: ' . $exception->getMessage());
    }
}
