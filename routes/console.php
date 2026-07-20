<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\CalculateRiskScoresJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule automatic risk score calculation every 6 hours
Schedule::job(new CalculateRiskScoresJob())
    ->everySixHours()
    ->name('calculate-risk-scores')
    ->withoutOverlapping()
    ->onSuccess(function () {
        \Log::info('✅ Scheduled risk calculation completed successfully');
    })
    ->onFailure(function () {
        \Log::error('❌ Scheduled risk calculation failed');
    });
