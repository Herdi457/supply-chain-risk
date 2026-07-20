<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Port;
use App\Models\Country;

class AddAntarcticaPorts extends Command
{
    protected $signature = 'ports:add-antarctica';
    protected $description = 'Add Antarctica research stations as ports';

    public function handle()
    {
        $this->info('🧊 Adding Antarctica research stations...');
        $this->newLine();
        
        // Check if Antarctica exists in countries table, if not create it
        $antarctica = Country::firstOrCreate(
            ['code' => 'AQ'],
            [
                'name' => 'Antarctica',
                'capital' => 'McMurdo Station',
                'region' => 'Antarctica',
                'population' => 1000,
                'area' => 14000000,
                'latitude' => -77.8500,
                'longitude' => 166.6667
            ]
        );
        
        $this->info("✅ Antarctica country: {$antarctica->name}");
        $this->newLine();
        
        $antarcticaStations = [
            // Major Research Stations with Port Facilities
            
            // USA
            ['name' => 'McMurdo Station (USA)', 'code' => 'AQ', 'lat' => -77.8500, 'lng' => 166.6667],
            ['name' => 'Palmer Station (USA)', 'code' => 'AQ', 'lat' => -64.7745, 'lng' => -64.0540],
            ['name' => 'Amundsen-Scott South Pole Station (USA)', 'code' => 'AQ', 'lat' => -90.0000, 'lng' => 0.0000],
            
            // Argentina
            ['name' => 'Esperanza Base (Argentina)', 'code' => 'AQ', 'lat' => -63.3986, 'lng' => -56.9965],
            ['name' => 'Marambio Base (Argentina)', 'code' => 'AQ', 'lat' => -64.2408, 'lng' => -56.6269],
            ['name' => 'Carlini Base (Argentina)', 'code' => 'AQ', 'lat' => -62.2391, 'lng' => -58.6617],
            
            // Chile
            ['name' => 'Villa Las Estrellas (Chile)', 'code' => 'AQ', 'lat' => -62.2000, 'lng' => -58.9667],
            ['name' => 'Presidente Eduardo Frei Base (Chile)', 'code' => 'AQ', 'lat' => -62.1958, 'lng' => -58.9867],
            
            // Russia
            ['name' => 'Vostok Station (Russia)', 'code' => 'AQ', 'lat' => -78.4644, 'lng' => 106.8378],
            ['name' => 'Novolazarevskaya Station (Russia)', 'code' => 'AQ', 'lat' => -70.7667, 'lng' => 11.8333],
            ['name' => 'Bellingshausen Station (Russia)', 'code' => 'AQ', 'lat' => -62.1958, 'lng' => -58.9619],
            
            // UK
            ['name' => 'Rothera Research Station (UK)', 'code' => 'AQ', 'lat' => -67.5674, 'lng' => -68.1268],
            ['name' => 'Halley Research Station (UK)', 'code' => 'AQ', 'lat' => -75.5833, 'lng' => -26.6667],
            
            // Australia
            ['name' => 'Casey Station (Australia)', 'code' => 'AQ', 'lat' => -66.2819, 'lng' => 110.5275],
            ['name' => 'Davis Station (Australia)', 'code' => 'AQ', 'lat' => -68.5767, 'lng' => 77.9674],
            ['name' => 'Mawson Station (Australia)', 'code' => 'AQ', 'lat' => -67.6050, 'lng' => 62.8708],
            
            // New Zealand
            ['name' => 'Scott Base (New Zealand)', 'code' => 'AQ', 'lat' => -77.8492, 'lng' => 166.7572],
            
            // China
            ['name' => 'Great Wall Station (China)', 'code' => 'AQ', 'lat' => -62.2167, 'lng' => -58.9667],
            ['name' => 'Zhongshan Station (China)', 'code' => 'AQ', 'lat' => -69.3733, 'lng' => 76.3714],
            ['name' => 'Kunlun Station (China)', 'code' => 'AQ', 'lat' => -80.3819, 'lng' => 77.0653],
            
            // Japan
            ['name' => 'Syowa Station (Japan)', 'code' => 'AQ', 'lat' => -69.0036, 'lng' => 39.5900],
            
            // South Korea
            ['name' => 'King Sejong Station (South Korea)', 'code' => 'AQ', 'lat' => -62.2233, 'lng' => -58.7850],
            
            // India
            ['name' => 'Maitri Station (India)', 'code' => 'AQ', 'lat' => -70.7667, 'lng' => 11.7333],
            ['name' => 'Bharati Station (India)', 'code' => 'AQ', 'lat' => -69.4108, 'lng' => 76.1908],
            
            // Germany
            ['name' => 'Neumayer Station III (Germany)', 'code' => 'AQ', 'lat' => -70.6667, 'lng' => -8.2667],
            
            // France
            ['name' => 'Dumont d\'Urville Station (France)', 'code' => 'AQ', 'lat' => -66.6650, 'lng' => 140.0014],
            
            // Brazil
            ['name' => 'Comandante Ferraz Station (Brazil)', 'code' => 'AQ', 'lat' => -62.0850, 'lng' => -58.3933],
            
            // Poland
            ['name' => 'Henryk Arctowski Station (Poland)', 'code' => 'AQ', 'lat' => -62.1600, 'lng' => -58.4733],
            
            // Norway
            ['name' => 'Troll Station (Norway)', 'code' => 'AQ', 'lat' => -72.0114, 'lng' => 2.5353],
            
            // Italy
            ['name' => 'Mario Zucchelli Station (Italy)', 'code' => 'AQ', 'lat' => -74.6944, 'lng' => 164.1167],
            
            // Belgium
            ['name' => 'Princess Elisabeth Station (Belgium)', 'code' => 'AQ', 'lat' => -71.9503, 'lng' => 23.3472],
        ];
        
        $progressBar = $this->output->createProgressBar(count($antarcticaStations));
        $progressBar->start();
        
        $added = 0;
        $skipped = 0;
        
        foreach ($antarcticaStations as $stationData) {
            try {
                // Check if station already exists
                $exists = Port::where('country_code', $stationData['code'])
                    ->where('port_name', $stationData['name'])
                    ->exists();
                
                if ($exists) {
                    $skipped++;
                    $progressBar->advance();
                    continue;
                }
                
                Port::create([
                    'port_name' => $stationData['name'],
                    'country_code' => $stationData['code'],
                    'latitude' => $stationData['lat'],
                    'longitude' => $stationData['lng'],
                    'index_number' => 'AQ-' . substr(md5($stationData['name']), 0, 8)
                ]);
                
                $added++;
                $progressBar->advance();
                
            } catch (\Exception $e) {
                $this->error("\n❌ Failed to add {$stationData['name']}: {$e->getMessage()}");
            }
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        $this->info("✅ Added {$added} Antarctica research stations");
        $this->info("⏭️  Skipped {$skipped} existing stations");
        $this->info("📊 Total ports now: " . Port::count());
        
        // Show stations by country
        $this->newLine();
        $this->info("🧊 Antarctica Research Stations:");
        $this->line("  🇺🇸 USA: McMurdo, Palmer, South Pole");
        $this->line("  🇦🇷 Argentina: Esperanza, Marambio, Carlini");
        $this->line("  🇨🇱 Chile: Villa Las Estrellas, Frei");
        $this->line("  🇷🇺 Russia: Vostok, Novolazarevskaya, Bellingshausen");
        $this->line("  🇬🇧 UK: Rothera, Halley");
        $this->line("  🇦🇺 Australia: Casey, Davis, Mawson");
        $this->line("  🇳🇿 New Zealand: Scott Base");
        $this->line("  🇨🇳 China: Great Wall, Zhongshan, Kunlun");
        $this->line("  🇯🇵 Japan: Syowa");
        $this->line("  🇰🇷 South Korea: King Sejong");
        $this->line("  🇮🇳 India: Maitri, Bharati");
        $this->line("  🇩🇪 Germany: Neumayer III");
        $this->line("  🇫🇷 France: Dumont d'Urville");
        $this->line("  🇧🇷 Brazil: Comandante Ferraz");
        $this->line("  + Poland, Norway, Italy, Belgium");
        
        return 0;
    }
}
