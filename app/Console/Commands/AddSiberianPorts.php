<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Port;

class AddSiberianPorts extends Command
{
    protected $signature = 'ports:add-siberian';
    protected $description = 'Add Siberian and Far East Russian ports to fill central/eastern Russia';

    public function handle()
    {
        $this->info('🌏 Adding Siberian & Far East Russian ports...');
        $this->newLine();
        
        $siberianPorts = [
            // Siberia - Arctic Coast
            ['name' => 'Port of Dikson', 'code' => 'RU', 'lat' => 73.5069, 'lng' => 80.5464],
            ['name' => 'Port of Tiksi', 'code' => 'RU', 'lat' => 71.6412, 'lng' => 128.8742],
            ['name' => 'Port of Dudinka', 'code' => 'RU', 'lat' => 69.4058, 'lng' => 86.1778],
            ['name' => 'Port of Igarka', 'code' => 'RU', 'lat' => 67.4667, 'lng' => 86.5833],
            ['name' => 'Port of Pevek', 'code' => 'RU', 'lat' => 69.7009, 'lng' => 170.3133],
            
            // Siberia - Major Rivers
            ['name' => 'Port of Novosibirsk', 'code' => 'RU', 'lat' => 55.0084, 'lng' => 82.9357],
            ['name' => 'Port of Krasnoyarsk', 'code' => 'RU', 'lat' => 56.0153, 'lng' => 92.8932],
            ['name' => 'Port of Irkutsk', 'code' => 'RU', 'lat' => 52.2870, 'lng' => 104.3050],
            ['name' => 'Port of Yakutsk', 'code' => 'RU', 'lat' => 62.0355, 'lng' => 129.6755],
            ['name' => 'Port of Khabarovsk', 'code' => 'RU', 'lat' => 48.4827, 'lng' => 135.0838],
            
            // Far East Russia - Pacific
            ['name' => 'Port of Nakhodka', 'code' => 'RU', 'lat' => 42.8133, 'lng' => 132.8736],
            ['name' => 'Port of Vostochny', 'code' => 'RU', 'lat' => 42.7386, 'lng' => 133.0486],
            ['name' => 'Port of Vanino', 'code' => 'RU', 'lat' => 49.0861, 'lng' => 140.2536],
            ['name' => 'Port of Korsakov', 'code' => 'RU', 'lat' => 46.6342, 'lng' => 142.7736],
            ['name' => 'Port of Yuzhno-Sakhalinsk', 'code' => 'RU', 'lat' => 46.9590, 'lng' => 142.7386],
            
            // Kamchatka Peninsula
            ['name' => 'Port of Ust-Kamchatsk', 'code' => 'RU', 'lat' => 56.2333, 'lng' => 162.4667],
            
            // Kuril Islands
            ['name' => 'Port of Yuzhno-Kurilsk', 'code' => 'RU', 'lat' => 44.0333, 'lng' => 145.8500],
            
            // Black Sea additional
            ['name' => 'Port of Sochi', 'code' => 'RU', 'lat' => 43.5855, 'lng' => 39.7231],
            ['name' => 'Port of Tuapse', 'code' => 'RU', 'lat' => 44.0978, 'lng' => 39.0694],
            
            // Caspian Sea
            ['name' => 'Port of Makhachkala', 'code' => 'RU', 'lat' => 42.9849, 'lng' => 47.5047],
            ['name' => 'Port of Astrakhan', 'code' => 'RU', 'lat' => 46.3497, 'lng' => 48.0408],
            
            // Baltic additional
            ['name' => 'Port of Kaliningrad', 'code' => 'RU', 'lat' => 54.7104, 'lng' => 20.5110],
            
            // China - Northern ports
            ['name' => 'Port of Dalian', 'code' => 'CN', 'lat' => 38.9140, 'lng' => 121.6147],
            ['name' => 'Port of Tianjin', 'code' => 'CN', 'lat' => 39.0842, 'lng' => 117.2010],
            ['name' => 'Port of Qingdao', 'code' => 'CN', 'lat' => 36.0667, 'lng' => 120.3826],
            
            // Mongolia (landlocked but river port)
            ['name' => 'Port of Ulaanbaatar', 'code' => 'MN', 'lat' => 47.8864, 'lng' => 106.9057],
            
            // Kazakhstan - Caspian
            ['name' => 'Port of Aktau', 'code' => 'KZ', 'lat' => 43.6508, 'lng' => 51.1600],
            
            // Japan - Northern
            ['name' => 'Port of Otaru', 'code' => 'JP', 'lat' => 43.1907, 'lng' => 140.9947],
            ['name' => 'Port of Hakodate', 'code' => 'JP', 'lat' => 41.7687, 'lng' => 140.7288],
            ['name' => 'Port of Niigata', 'code' => 'JP', 'lat' => 37.9161, 'lng' => 139.0364],
            
            // South Korea - Additional
            ['name' => 'Port of Incheon', 'code' => 'KR', 'lat' => 37.4563, 'lng' => 126.7052],
            ['name' => 'Port of Ulsan', 'code' => 'KR', 'lat' => 35.5384, 'lng' => 129.3114],
        ];
        
        $progressBar = $this->output->createProgressBar(count($siberianPorts));
        $progressBar->start();
        
        $added = 0;
        $skipped = 0;
        
        foreach ($siberianPorts as $portData) {
            try {
                // Check if similar port already exists
                $exists = Port::where('country_code', $portData['code'])
                    ->where(function($q) use ($portData) {
                        $q->where('port_name', 'LIKE', '%' . $portData['name'] . '%')
                          ->orWhere(function($q2) use ($portData) {
                              $q2->whereBetween('latitude', [$portData['lat'] - 0.5, $portData['lat'] + 0.5])
                                 ->whereBetween('longitude', [$portData['lng'] - 0.5, $portData['lng'] + 0.5]);
                          });
                    })
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
        
        $this->info("✅ Added {$added} Siberian/Far East ports");
        $this->info("⏭️  Skipped {$skipped} existing ports");
        $this->info("📊 Total ports now: " . Port::count());
        
        // Show coverage
        $this->newLine();
        $this->info("🌏 Siberia & Far East coverage:");
        $this->line("  ❄️ Arctic Russia: Dikson, Tiksi, Dudinka, Pevek");
        $this->line("  🏔️ Central Siberia: Novosibirsk, Krasnoyarsk, Irkutsk");
        $this->line("  🌊 Far East: Vladivostok, Nakhodka, Khabarovsk");
        $this->line("  🗻 Kamchatka: Petropavlovsk, Ust-Kamchatsk");
        $this->line("  🇨🇳 North China: Dalian, Tianjin, Qingdao");
        $this->line("  🇯🇵 North Japan: Otaru, Hakodate, Niigata");
        $this->line("  🇰🇷 Korea: Incheon, Ulsan");
        
        return 0;
    }
}
