<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Port;

class CheckWeatherPorts extends Command
{
    protected $signature = 'check:weather-ports';
    protected $description = 'Check ports loaded for weather monitoring page';

    public function handle()
    {
        $this->info('🌤️ Checking weather monitoring ports...');
        $this->newLine();
        
        // Replicate weather route query
        $ports = Port::select('id', 'port_name', 'country_code', 'latitude', 'longitude')
            ->with('country:id,code,name')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();
        
        $this->info("📊 Total ports for weather: {$ports->count()}");
        $this->newLine();
        
        // Check Russia
        $russia = $ports->where('country_code', 'RU')->first();
        
        if ($russia) {
            $this->info('✅ RUSSIA in weather data:');
            $this->line("  Port: {$russia->port_name}");
            $this->line("  Coords: {$russia->latitude}, {$russia->longitude}");
            $this->line("  Country: " . ($russia->country->name ?? 'N/A'));
        } else {
            $this->error('❌ RUSSIA NOT in weather data!');
        }
        
        $this->newLine();
        $this->info('🌍 Countries covered: ' . $ports->pluck('country_code')->unique()->count());
        
        return 0;
    }
}
