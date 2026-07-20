<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Port;

class AddNorthernPorts extends Command
{
    protected $signature = 'ports:add-northern';
    protected $description = 'Add northern/Arctic ports to fill the empty northern regions';

    public function handle()
    {
        $this->info('🌍 Adding northern/Arctic ports...');
        $this->newLine();
        
        $northernPorts = [
            // Russia - Northern & Far East
            ['name' => 'Port of Murmansk', 'code' => 'RU', 'lat' => 68.9700, 'lng' => 33.0750],
            ['name' => 'Port of Arkhangelsk', 'code' => 'RU', 'lat' => 64.5401, 'lng' => 40.5433],
            ['name' => 'Port of Vladivostok', 'code' => 'RU', 'lat' => 43.1150, 'lng' => 131.8855],
            ['name' => 'Port of Novorossiysk', 'code' => 'RU', 'lat' => 44.7230, 'lng' => 37.7690],
            ['name' => 'Port of Magadan', 'code' => 'RU', 'lat' => 59.5625, 'lng' => 150.8084],
            ['name' => 'Port of Petropavlovsk-Kamchatsky', 'code' => 'RU', 'lat' => 53.0245, 'lng' => 158.6434],
            
            // Norway - Arctic
            ['name' => 'Port of Tromsø', 'code' => 'NO', 'lat' => 69.6492, 'lng' => 18.9553],
            ['name' => 'Port of Narvik', 'code' => 'NO', 'lat' => 68.4385, 'lng' => 17.4272],
            ['name' => 'Port of Hammerfest', 'code' => 'NO', 'lat' => 70.6634, 'lng' => 23.6821],
            ['name' => 'Port of Bergen', 'code' => 'NO', 'lat' => 60.3913, 'lng' => 5.3221],
            ['name' => 'Port of Trondheim', 'code' => 'NO', 'lat' => 63.4305, 'lng' => 10.3951],
            
            // Sweden - Northern
            ['name' => 'Port of Luleå', 'code' => 'SE', 'lat' => 65.5848, 'lng' => 22.1547],
            ['name' => 'Port of Umeå', 'code' => 'SE', 'lat' => 63.8258, 'lng' => 20.2630],
            ['name' => 'Port of Gävle', 'code' => 'SE', 'lat' => 60.6749, 'lng' => 17.1413],
            
            // Finland - Northern
            ['name' => 'Port of Oulu', 'code' => 'FI', 'lat' => 65.0121, 'lng' => 25.4651],
            ['name' => 'Port of Kemi', 'code' => 'FI', 'lat' => 65.7360, 'lng' => 24.5660],
            ['name' => 'Port of Turku', 'code' => 'FI', 'lat' => 60.4518, 'lng' => 22.2666],
            
            // Iceland - Strategic Atlantic
            ['name' => 'Port of Reykjavik', 'code' => 'IS', 'lat' => 64.1466, 'lng' => -21.9426],
            ['name' => 'Port of Akureyri', 'code' => 'IS', 'lat' => 65.6835, 'lng' => -18.0878],
            
            // Canada - Northern & Arctic
            ['name' => 'Port of Churchill', 'code' => 'CA', 'lat' => 58.7684, 'lng' => -94.1648],
            ['name' => 'Port of Iqaluit', 'code' => 'CA', 'lat' => 63.7467, 'lng' => -68.5170],
            ['name' => 'Port of Halifax', 'code' => 'CA', 'lat' => 44.6488, 'lng' => -63.5752],
            ['name' => 'Port of Montreal', 'code' => 'CA', 'lat' => 45.5017, 'lng' => -73.5673],
            ['name' => 'Port of Prince Rupert', 'code' => 'CA', 'lat' => 54.3150, 'lng' => -130.3209],
            
            // USA - Alaska
            ['name' => 'Port of Anchorage', 'code' => 'US', 'lat' => 61.2181, 'lng' => -149.9003],
            ['name' => 'Port of Juneau', 'code' => 'US', 'lat' => 58.3019, 'lng' => -134.4197],
            ['name' => 'Port of Nome', 'code' => 'US', 'lat' => 64.5011, 'lng' => -165.4064],
            
            // Greenland (Denmark)
            ['name' => 'Port of Nuuk', 'code' => 'DK', 'lat' => 64.1814, 'lng' => -51.6941],
            
            // Estonia
            ['name' => 'Port of Tallinn', 'code' => 'EE', 'lat' => 59.4370, 'lng' => 24.7536],
            
            // Latvia
            ['name' => 'Port of Riga', 'code' => 'LV', 'lat' => 56.9496, 'lng' => 24.1052],
            
            // Lithuania
            ['name' => 'Port of Klaipėda', 'code' => 'LT', 'lat' => 55.7033, 'lng' => 21.1443],
            
            // Poland - Baltic
            ['name' => 'Port of Gdynia', 'code' => 'PL', 'lat' => 54.5189, 'lng' => 18.5305],
            
            // Germany - Baltic & North Sea
            ['name' => 'Port of Rostock', 'code' => 'DE', 'lat' => 54.0887, 'lng' => 12.1449],
            ['name' => 'Port of Kiel', 'code' => 'DE', 'lat' => 54.3233, 'lng' => 10.1394],
            
            // Denmark - Additional
            ['name' => 'Port of Aarhus', 'code' => 'DK', 'lat' => 56.1629, 'lng' => 10.2039],
            ['name' => 'Port of Copenhagen', 'code' => 'DK', 'lat' => 55.6761, 'lng' => 12.5683],
            
            // UK - Scotland
            ['name' => 'Port of Aberdeen', 'code' => 'GB', 'lat' => 57.1497, 'lng' => -2.0943],
            ['name' => 'Port of Lerwick', 'code' => 'GB', 'lat' => 60.1553, 'lng' => -1.1450],
            
            // Ireland
            ['name' => 'Port of Dublin', 'code' => 'IE', 'lat' => 53.3498, 'lng' => -6.2603],
            ['name' => 'Port of Cork', 'code' => 'IE', 'lat' => 51.8985, 'lng' => -8.4756],
        ];
        
        $progressBar = $this->output->createProgressBar(count($northernPorts));
        $progressBar->start();
        
        $added = 0;
        $skipped = 0;
        
        foreach ($northernPorts as $portData) {
            try {
                // Check if similar port already exists
                $exists = Port::where('country_code', $portData['code'])
                    ->where('port_name', 'LIKE', '%' . $portData['name'] . '%')
                    ->exists();
                
                if ($exists) {
                    $skipped++;
                    $progressBar->advance();
                    continue;
                }
                
                Port::create([
                    'port_name' => $portData['name'],
                    'country_code' => $portData['code'],
                    'latitude' => $portData['lat'],
                    'longitude' => $portData['lng'],
                    'index_number' => 'PORT-' . $portData['code'] . '-' . substr(md5($portData['name']), 0, 6)
                ]);
                
                $added++;
                $progressBar->advance();
                
            } catch (\Exception $e) {
                $this->error("\n❌ Failed to add {$portData['name']}: {$e->getMessage()}");
            }
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        $this->info("✅ Added {$added} northern ports");
        $this->info("⏭️  Skipped {$skipped} existing ports");
        $this->info("📊 Total ports now: " . Port::count());
        
        // Show Arctic ports added
        $this->newLine();
        $this->info("🌍 Arctic/Northern coverage improved:");
        $this->line("  🇷🇺 Russia: Murmansk, Arkhangelsk, Vladivostok, etc.");
        $this->line("  🇳🇴 Norway: Tromsø, Narvik, Hammerfest");
        $this->line("  🇸🇪 Sweden: Luleå, Umeå");
        $this->line("  🇫🇮 Finland: Oulu, Kemi");
        $this->line("  🇮🇸 Iceland: Reykjavik, Akureyri");
        $this->line("  🇨🇦 Canada: Churchill, Iqaluit, Halifax");
        $this->line("  🇺🇸 USA: Anchorage, Juneau, Nome (Alaska)");
        
        return 0;
    }
}
