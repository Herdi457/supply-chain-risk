<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveDuplicatePorts extends Command
{
    protected $signature = 'ports:remove-duplicates {--force}';
    protected $description = 'Remove duplicate ports and ports with city coordinates (not actual port coordinates)';

    public function handle()
    {
        $force = $this->option('force');
        
        if (!$force) {
            if (!$this->confirm('This will remove duplicate and incorrect port entries. Continue?')) {
                return;
            }
        }

        $this->info('🧹 Removing duplicate and incorrect ports...');
        
        $total = DB::table('ports')->count();
        $this->info("Current total: {$total} ports");
        
        $deleted = 0;
        
        // 1. Remove ports with "Main Port of [Country]" pattern (from old seeder)
        $count = DB::table('ports')
            ->where('port_name', 'LIKE', 'Main Port of %')
            ->delete();
        $deleted += $count;
        $this->info("Deleted {$count} 'Main Port of' entries");
        
        // 2. Remove ports with "Port of [City]" that are at city center, not actual port
        $cityPorts = [
            ['name' => 'Port of Medan', 'actual' => 'Belawan'],
            ['name' => 'Port of Palembang', 'actual' => 'Boom Baru'],
            ['name' => 'Port of Jakarta', 'actual' => 'Tanjung Priok'],
        ];
        
        foreach ($cityPorts as $cp) {
            $count = DB::table('ports')
                ->where('port_name', $cp['name'])
                ->delete();
            $deleted += $count;
            if ($count > 0) {
                $this->info("Deleted '{$cp['name']}' (actual port: {$cp['actual']})");
            }
        }
        
        // 3. Remove exact duplicates by coordinates
        $duplicates = DB::select("
            SELECT latitude, longitude, COUNT(*) as count, GROUP_CONCAT(id) as ids
            FROM ports
            GROUP BY latitude, longitude
            HAVING COUNT(*) > 1
        ");
        
        foreach ($duplicates as $dup) {
            $ids = explode(',', $dup->ids);
            // Keep first, delete rest
            array_shift($ids);
            if (!empty($ids)) {
                $count = DB::table('ports')->whereIn('id', $ids)->delete();
                $deleted += $count;
                $this->info("Removed {$count} duplicate(s) at ({$dup->latitude}, {$dup->longitude})");
            }
        }
        
        $remaining = DB::table('ports')->count();
        
        $this->info("✅ Total deleted: {$deleted} ports");
        $this->info("📊 Remaining: {$remaining} accurate unique ports");
        
        return 0;
    }
}
