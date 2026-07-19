<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Port;
use App\Models\Country;

class CheckRussiaPort extends Command
{
    protected $signature = 'check:russia';
    protected $description = 'Check if Russia port exists and display details';

    public function handle()
    {
        $this->info('🔍 Checking Russia port data...');
        $this->newLine();
        
        // Check port
        $port = Port::where('country_code', 'RU')->first();
        
        if ($port) {
            $this->info('✅ Russia port FOUND:');
            $this->line("  Port Name: {$port->port_name}");
            $this->line("  Country Code: {$port->country_code}");
            $this->line("  Latitude: {$port->latitude}");
            $this->line("  Longitude: {$port->longitude}");
            
            if ($port->country) {
                $this->line("  Country Name: {$port->country->name}");
            }
        } else {
            $this->error('❌ Russia port NOT FOUND in database!');
        }
        
        $this->newLine();
        
        // Check country
        $country = Country::where('code', 'RU')->first();
        if ($country) {
            $this->info('✅ Russia country data:');
            $this->line("  Name: {$country->name}");
            $this->line("  Capital: {$country->capital}");
            $this->line("  Lat: {$country->latitude}");
            $this->line("  Lng: {$country->longitude}");
        } else {
            $this->error('❌ Russia country NOT FOUND!');
        }
        
        $this->newLine();
        $this->info('📊 Total ports in database: ' . Port::count());
        $this->info('📊 Countries with ports: ' . Port::distinct('country_code')->count('country_code'));
        
        return 0;
    }
}
