<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CalculateRiskScoresJob;

class CalculateAllRisks extends Command
{
    protected $signature = 'risk:calculate-all {--sync : Run synchronously without queue}';
    protected $description = 'Calculate risk scores for all countries (auto-runs every 6 hours)';

    public function handle()
    {
        $this->info('🚀 Starting risk score calculation for all countries...');
        
        if ($this->option('sync')) {
            // Run synchronously (for testing/debugging)
            $this->info('Running in SYNC mode...');
            $job = new CalculateRiskScoresJob();
            $job->handle(app(\App\Services\RiskCalculationService::class));
            $this->info('✅ Calculation completed!');
        } else {
            // Dispatch to queue (recommended for production)
            $this->info('Dispatching to queue...');
            CalculateRiskScoresJob::dispatch();
            $this->info('✅ Job dispatched to queue! Check logs for progress.');
        }
        
        return 0;
    }
}
