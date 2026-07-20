<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckPortAccuracy extends Command
{
    protected $signature = 'ports:check-accuracy {country?}';
    protected $description = 'Check accuracy of port coordinates. Optional: specify country code to list all ports';

    public function handle()
    {
        $countryCode = $this->argument('country');
        
        if ($countryCode) {
            $this->listCountryPorts($countryCode);
            return;
        }
        
        $this->info('🔍 Checking port coordinate accuracy...');
        $this->info('');
        
        // Major ports with known accurate coordinates for verification
        $knownPorts = [
            'Port of Singapore' => ['expected' => [1.2644, 103.8222], 'tolerance' => 0.1],
            'Port of Shanghai' => ['expected' => [31.2304, 121.4737], 'tolerance' => 0.5],
            'Port of Rotterdam' => ['expected' => [51.9225, 4.4792], 'tolerance' => 0.1],
            'Port of Los Angeles' => ['expected' => [33.7401, -118.2701], 'tolerance' => 0.1],
            'Port of Tokyo' => ['expected' => [35.6528, 139.7594], 'tolerance' => 0.2],
            'Port of Hamburg' => ['expected' => [53.5511, 9.9937], 'tolerance' => 0.1],
            'Port of Tanjung Priok' => ['expected' => [-6.1067, 106.8867], 'tolerance' => 0.05],
        ];
        
        foreach ($knownPorts as $portName => $data) {
            $port = DB::table('ports')
                ->where('port_name', 'LIKE', "%{$portName}%")
                ->first();
                
            if ($port) {
                $latDiff = abs($port->latitude - $data['expected'][0]);
                $lonDiff = abs($port->longitude - $data['expected'][1]);
                
                $accurate = ($latDiff <= $data['tolerance'] && $lonDiff <= $data['tolerance']);
                
                $status = $accurate ? '✅ ACCURATE' : '⚠️ NEEDS CHECK';
                $this->info("{$status} {$port->port_name} ({$port->country_code})");
                $this->line("  Database:  {$port->latitude}, {$port->longitude}");
                $this->line("  Expected:  {$data['expected'][0]}, {$data['expected'][1]}");
                $this->line("  Diff:      Lat ±" . number_format($latDiff, 4) . "°, Lon ±" . number_format($lonDiff, 4) . "°");
                $this->info('');
            } else {
                $this->error("❌ {$portName} not found in database");
                $this->info('');
            }
        }
        
        // Sample random ports from different regions
        $this->info('📍 Random sample from database:');
        $this->info('');
        
        $samples = DB::table('ports')
            ->inRandomOrder()
            ->limit(10)
            ->get(['port_name', 'country_code', 'latitude', 'longitude']);
            
        foreach ($samples as $port) {
            $this->line("  {$port->port_name} ({$port->country_code}): {$port->latitude}, {$port->longitude}");
        }
        
        $this->info('');
        $this->info('✅ Accuracy check complete!');
    }
    
    private function listCountryPorts($countryCode)
    {
        $countryCode = strtoupper($countryCode);
        $this->info("🌍 Ports in {$countryCode}:");
        $this->info('');
        
        $ports = DB::table('ports')
            ->where('country_code', $countryCode)
            ->orderBy('port_name')
            ->get(['port_name', 'latitude', 'longitude']);
            
        if ($ports->isEmpty()) {
            $this->error("No ports found for country code: {$countryCode}");
            return;
        }
        
        foreach ($ports as $port) {
            $this->line(sprintf(
                "%-50s %10s, %11s",
                $port->port_name,
                number_format($port->latitude, 4),
                number_format($port->longitude, 4)
            ));
        }
        
        $this->info('');
        $this->info("Total: {$ports->count()} ports");
    }
}
