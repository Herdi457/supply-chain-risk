<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddCanadianArcticPorts extends Command
{
    protected $signature = 'ports:add-canadian-arctic';
    protected $description = 'Add comprehensive Canadian Arctic archipelago ports (Nunavut, NWT, Arctic islands)';

    public function handle()
    {
        $this->info('🇨🇦 Adding Canadian Arctic archipelago ports...');
        
        $ports = [
            // ============ NUNAVUT - BAFFIN ISLAND ============
            ['port_name' => 'Iqaluit Port', 'country_code' => 'CA', 'latitude' => 63.7467, 'longitude' => -68.5170],
            ['port_name' => 'Arctic Bay', 'country_code' => 'CA', 'latitude' => 73.0333, 'longitude' => -85.1500],
            ['port_name' => 'Pond Inlet', 'country_code' => 'CA', 'latitude' => 72.6989, 'longitude' => -77.9658],
            ['port_name' => 'Clyde River', 'country_code' => 'CA', 'latitude' => 70.4728, 'longitude' => -68.5914],
            ['port_name' => 'Pangnirtung', 'country_code' => 'CA', 'latitude' => 66.1450, 'longitude' => -65.7133],
            ['port_name' => 'Qikiqtarjuaq', 'country_code' => 'CA', 'latitude' => 67.5667, 'longitude' => -64.0167],
            ['port_name' => 'Cape Dorset', 'country_code' => 'CA', 'latitude' => 64.2300, 'longitude' => -76.5269],
            ['port_name' => 'Kimmirut', 'country_code' => 'CA', 'latitude' => 62.8500, 'longitude' => -69.8833],
            
            // ============ NUNAVUT - MAINLAND & ISLANDS ============
            ['port_name' => 'Resolute Bay', 'country_code' => 'CA', 'latitude' => 74.6956, 'longitude' => -94.8292],
            ['port_name' => 'Cambridge Bay', 'country_code' => 'CA', 'latitude' => 69.1139, 'longitude' => -105.0528],
            ['port_name' => 'Gjoa Haven', 'country_code' => 'CA', 'latitude' => 68.6264, 'longitude' => -95.8797],
            ['port_name' => 'Kugaaruk', 'country_code' => 'CA', 'latitude' => 68.5344, 'longitude' => -89.8089],
            ['port_name' => 'Taloyoak', 'country_code' => 'CA', 'latitude' => 69.5364, 'longitude' => -93.5456],
            ['port_name' => 'Kugluktuk', 'country_code' => 'CA', 'latitude' => 67.8269, 'longitude' => -115.0961],
            ['port_name' => 'Rankin Inlet', 'country_code' => 'CA', 'latitude' => 62.8097, 'longitude' => -92.0892],
            ['port_name' => 'Arviat', 'country_code' => 'CA', 'latitude' => 61.1089, 'longitude' => -94.0586],
            ['port_name' => 'Chesterfield Inlet', 'country_code' => 'CA', 'latitude' => 63.3461, 'longitude' => -90.7050],
            ['port_name' => 'Baker Lake', 'country_code' => 'CA', 'latitude' => 64.3189, 'longitude' => -96.0158],
            ['port_name' => 'Coral Harbour', 'country_code' => 'CA', 'latitude' => 64.1344, 'longitude' => -83.1594],
            ['port_name' => 'Repulse Bay', 'country_code' => 'CA', 'latitude' => 66.5214, 'longitude' => -86.2486],
            
            // ============ NUNAVUT - HIGH ARCTIC ============
            ['port_name' => 'Grise Fiord', 'country_code' => 'CA', 'latitude' => 76.4200, 'longitude' => -82.9000],
            ['port_name' => 'Nanisivik', 'country_code' => 'CA', 'latitude' => 73.0333, 'longitude' => -84.5500],
            ['port_name' => 'Eureka Research Station', 'country_code' => 'CA', 'latitude' => 79.9889, 'longitude' => -85.9408],
            ['port_name' => 'Alert Station', 'country_code' => 'CA', 'latitude' => 82.5018, 'longitude' => -62.3481],
            
            // ============ NORTHWEST TERRITORIES ============
            ['port_name' => 'Tuktoyaktuk', 'country_code' => 'CA', 'latitude' => 69.4450, 'longitude' => -133.0367],
            ['port_name' => 'Inuvik', 'country_code' => 'CA', 'latitude' => 68.3607, 'longitude' => -133.7230],
            ['port_name' => 'Paulatuk', 'country_code' => 'CA', 'latitude' => 69.3608, 'longitude' => -124.0750],
            ['port_name' => 'Sachs Harbour', 'country_code' => 'CA', 'latitude' => 71.9894, 'longitude' => -125.2428],
            ['port_name' => 'Ulukhaktok', 'country_code' => 'CA', 'latitude' => 70.7331, 'longitude' => -117.7669],
            ['port_name' => 'Yellowknife', 'country_code' => 'CA', 'latitude' => 62.4540, 'longitude' => -114.3718],
            ['port_name' => 'Hay River', 'country_code' => 'CA', 'latitude' => 60.8156, 'longitude' => -115.7999],
            
            // ============ YUKON ============
            ['port_name' => 'Whitehorse', 'country_code' => 'CA', 'latitude' => 60.7212, 'longitude' => -135.0568],
            ['port_name' => 'Dawson City', 'country_code' => 'CA', 'latitude' => 64.0608, 'longitude' => -139.4281],
            ['port_name' => 'Old Crow', 'country_code' => 'CA', 'latitude' => 67.5706, 'longitude' => -139.8397],
            
            // ============ HUDSON BAY REGION ============
            ['port_name' => 'Churchill', 'country_code' => 'CA', 'latitude' => 58.7684, 'longitude' => -94.1648],
            ['port_name' => 'Sanikiluaq', 'country_code' => 'CA', 'latitude' => 56.5378, 'longitude' => -79.2247],
            
            // ============ VICTORIA ISLAND & BANKS ISLAND ============
            ['port_name' => 'Holman (Ulukhaktok)', 'country_code' => 'CA', 'latitude' => 70.7472, 'longitude' => -117.7108],
            ['port_name' => 'Minto Inlet', 'country_code' => 'CA', 'latitude' => 71.5333, 'longitude' => -115.1500],
            
            // ============ QUEEN ELIZABETH ISLANDS ============
            ['port_name' => 'Mould Bay', 'country_code' => 'CA', 'latitude' => 76.2333, 'longitude' => -119.3333],
            ['port_name' => 'Isachsen', 'country_code' => 'CA', 'latitude' => 78.7833, 'longitude' => -103.5333],
        ];
        
        $added = 0;
        $skipped = 0;
        
        foreach ($ports as $port) {
            $exists = DB::table('ports')
                ->where('latitude', $port['latitude'])
                ->where('longitude', $port['longitude'])
                ->exists();
                
            if (!$exists) {
                DB::table('ports')->insert([
                    'port_name' => $port['port_name'],
                    'country_code' => $port['country_code'],
                    'latitude' => $port['latitude'],
                    'longitude' => $port['longitude'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $added++;
                $this->info("✓ Added: {$port['port_name']}");
            } else {
                $skipped++;
            }
        }
        
        $this->info("✅ Canadian Arctic ports added: {$added}");
        $this->info("⏭️  Skipped (duplicates): {$skipped}");
        
        $total = DB::table('ports')->count();
        $this->info("📊 Total ports in database: {$total}");
        
        $canadianTotal = DB::table('ports')->where('country_code', 'CA')->count();
        $this->info("🇨🇦 Canadian ports: {$canadianTotal}");
    }
}
