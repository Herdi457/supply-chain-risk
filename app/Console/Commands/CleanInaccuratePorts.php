<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanInaccuratePorts extends Command
{
    protected $signature = 'ports:clean-inaccurate {--force}';
    protected $description = 'Remove inaccurate/random generated ports (keep only original accurate ones)';

    public function handle()
    {
        $force = $this->option('force');
        
        if (!$force) {
            if (!$this->confirm('This will delete ports with random/inaccurate coordinates. Continue?')) {
                return;
            }
        }

        $this->info('🧹 Cleaning inaccurate ports...');
        
        $total = DB::table('ports')->count();
        $this->info("Current total: {$total} ports");
        
        // Delete ports with pattern names (generated ports)
        $deleted = DB::table('ports')
            ->where('port_name', 'LIKE', '%Port %-_%')
            ->orWhere('port_name', 'LIKE', '%Harbor %-_%')
            ->orWhere('port_name', 'LIKE', '%Terminal %-_%')
            ->orWhere('port_name', 'LIKE', '%Marina %-_%')
            ->orWhere('port_name', 'LIKE', '%Wharf %-_%')
            ->orWhere('port_name', 'LIKE', '%Dock %-_%')
            ->orWhere('port_name', 'LIKE', '%Seaport %-_%')
            ->orWhere('port_name', 'LIKE', '%Naval Base %-_%')
            ->orWhere('port_name', 'LIKE', '%Ferry Terminal %-_%')
            ->orWhere('port_name', 'LIKE', '%Fishing Port %-_%')
            ->orWhere('port_name', 'LIKE', '%Commercial Port %-_%')
            ->orWhere('port_name', 'LIKE', '%Container Terminal %-_%')
            ->orWhere('port_name', 'LIKE', '%Cargo Port %-_%')
            ->orWhere('port_name', 'LIKE', '%Oil Terminal %-_%')
            ->orWhere('port_name', 'LIKE', '%Industrial Port %-_%')
            ->orWhere('port_name', 'LIKE', '%Regional Port %-_%')
            ->orWhere('port_name', 'LIKE', '%Small Port %-_%')
            ->orWhere('port_name', 'LIKE', '%Anchorage %-_%')
            ->orWhere('port_name', 'LIKE', '%Port Facility %-_%')
            ->orWhere('port_name', 'LIKE', '%Maritime Station %-_%')
            ->orWhere('port_name', 'LIKE', 'North %(%)')
            ->orWhere('port_name', 'LIKE', 'South %(%)')
            ->orWhere('port_name', 'LIKE', 'East %(%)')
            ->orWhere('port_name', 'LIKE', 'West %(%)')
            ->orWhere('port_name', 'LIKE', 'Central %(%)')
            ->orWhere('port_name', 'LIKE', 'Bay %(%)')
            ->orWhere('port_name', 'LIKE', 'Coast %(%)')
            ->orWhere('port_name', 'LIKE', 'River %(%)')
            ->orWhere('port_name', 'LIKE', 'Delta %(%)')
            ->orWhere('port_name', 'LIKE', 'Island %(%)')
            ->delete();
        
        $remaining = DB::table('ports')->count();
        
        $this->info("✅ Deleted: {$deleted} inaccurate ports");
        $this->info("📊 Remaining: {$remaining} accurate ports");
        
        return 0;
    }
}
