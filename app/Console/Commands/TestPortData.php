<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Port;

class TestPortData extends Command
{
    protected $signature = 'test:port-data';
    protected $description = 'Test port data that will be passed to map view';

    public function handle()
    {
        $this->info('🧪 Testing port data for map view...');
        $this->newLine();
        
        // Replicate the exact query from routes/web.php
        $ports = Port::select('id', 'port_name', 'country_code', 'latitude', 'longitude')
            ->with('country:id,code,name')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function($port) {
                return [
                    'id' => $port->id,
                    'port_name' => $port->port_name,
                    'country_code' => $port->country_code,
                    'country_name' => $port->country->name ?? $port->country_code,
                    'latitude' => $port->latitude,
                    'longitude' => $port->longitude
                ];
            });
        
        $this->info("📊 Total ports loaded: {$ports->count()}");
        $this->newLine();
        
        // Check Russia
        $russia = $ports->where('country_code', 'RU')->first();
        
        if ($russia) {
            $this->info('✅ RUSSIA FOUND in dataset:');
            $this->line(json_encode($russia, JSON_PRETTY_PRINT));
        } else {
            $this->error('❌ RUSSIA NOT FOUND in dataset!');
        }
        
        $this->newLine();
        
        // Show sample of 5 ports
        $this->info('📍 Sample ports (first 5):');
        $ports->take(5)->each(function($port) {
            $this->line("  • {$port['port_name']} ({$port['country_code']}) at {$port['latitude']}, {$port['longitude']}");
        });
        
        return 0;
    }
}
