<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddComprehensivePorts extends Command
{
    protected $signature = 'ports:add-comprehensive {--force}';
    protected $description = 'Add comprehensive port coverage for Africa, South America, Middle East, Pacific, Caribbean, Mediterranean, Southeast Asia';

    public function handle()
    {
        $force = $this->option('force');
        
        if (!$force) {
            if (!$this->confirm('This will add ~200 new ports. Continue?')) {
                return;
            }
        }

        $this->info('🌍 Adding comprehensive global port coverage...');
        
        $ports = $this->getComprehensivePorts();
        
        $added = 0;
        $skipped = 0;
        
        foreach ($ports as $port) {
            $exists = DB::table('ports')
                ->where('latitude', $port['latitude'])
                ->where('longitude', $port['longitude'])
                ->exists();
                
            if (!$exists) {
                DB::table('ports')->insert([
                    'port_name' => $port['name'],
                    'country_code' => $port['country_code'],
                    'latitude' => $port['latitude'],
                    'longitude' => $port['longitude'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $added++;
                $this->info("✓ Added: {$port['name']} ({$port['country_code']})");
            } else {
                $skipped++;
            }
        }
        
        $this->info("✅ Comprehensive ports added: {$added}");
        $this->info("⏭️  Skipped (duplicates): {$skipped}");
        
        $total = DB::table('ports')->count();
        $this->info("📊 Total ports in database: {$total}");
    }

    private function getComprehensivePorts()
    {
        return [
            // ============ AFRIKA - WEST COAST ============
            ['name' => 'Port of Lagos', 'country_code' => 'NG', 'latitude' => 6.4433, 'longitude' => 3.3915, 'type' => 'seaport'],
            ['name' => 'Port Harcourt', 'country_code' => 'NG', 'latitude' => 4.7774, 'longitude' => 7.0134, 'type' => 'seaport'],
            ['name' => 'Port of Abidjan', 'country_code' => 'CI', 'latitude' => 5.2893, 'longitude' => -3.9872, 'type' => 'seaport'],
            ['name' => 'Port of Dakar', 'country_code' => 'SN', 'latitude' => 14.6937, 'longitude' => -17.4441, 'type' => 'seaport'],
            ['name' => 'Port of Conakry', 'country_code' => 'GN', 'latitude' => 9.5092, 'longitude' => -13.7122, 'type' => 'seaport'],
            ['name' => 'Port of Freetown', 'country_code' => 'SL', 'latitude' => 8.4657, 'longitude' => -13.2317, 'type' => 'seaport'],
            ['name' => 'Port of Monrovia', 'country_code' => 'LR', 'latitude' => 6.3156, 'longitude' => -10.8074, 'type' => 'seaport'],
            ['name' => 'Port of Accra', 'country_code' => 'GH', 'latitude' => 5.6037, 'longitude' => -0.1870, 'type' => 'seaport'],
            ['name' => 'Port of Lomé', 'country_code' => 'TG', 'latitude' => 6.1319, 'longitude' => 1.2315, 'type' => 'seaport'],
            ['name' => 'Port of Cotonou', 'country_code' => 'BJ', 'latitude' => 6.3654, 'longitude' => 2.4183, 'type' => 'seaport'],
            
            // ============ AFRIKA - EAST COAST ============
            ['name' => 'Port of Mombasa', 'country_code' => 'KE', 'latitude' => -4.0435, 'longitude' => 39.6682, 'type' => 'seaport'],
            ['name' => 'Port of Dar es Salaam', 'country_code' => 'TZ', 'latitude' => -6.8160, 'longitude' => 39.2803, 'type' => 'seaport'],
            ['name' => 'Port of Maputo', 'country_code' => 'MZ', 'latitude' => -25.9655, 'longitude' => 32.5832, 'type' => 'seaport'],
            ['name' => 'Port of Beira', 'country_code' => 'MZ', 'latitude' => -19.8436, 'longitude' => 34.8389, 'type' => 'seaport'],
            ['name' => 'Port of Nacala', 'country_code' => 'MZ', 'latitude' => -14.5428, 'longitude' => 40.6773, 'type' => 'seaport'],
            ['name' => 'Port of Djibouti', 'country_code' => 'DJ', 'latitude' => 11.5950, 'longitude' => 43.1481, 'type' => 'seaport'],
            ['name' => 'Port Sudan', 'country_code' => 'SD', 'latitude' => 19.6159, 'longitude' => 37.2164, 'type' => 'seaport'],
            ['name' => 'Port of Mogadishu', 'country_code' => 'SO', 'latitude' => 2.0469, 'longitude' => 45.3182, 'type' => 'seaport'],
            ['name' => 'Port of Berbera', 'country_code' => 'SO', 'latitude' => 10.4396, 'longitude' => 45.0143, 'type' => 'seaport'],
            ['name' => 'Port of Zanzibar', 'country_code' => 'TZ', 'latitude' => -6.1659, 'longitude' => 39.1983, 'type' => 'seaport'],
            
            // ============ AFRIKA - NORTH COAST ============
            ['name' => 'Port of Alexandria', 'country_code' => 'EG', 'latitude' => 31.2001, 'longitude' => 29.9187, 'type' => 'seaport'],
            ['name' => 'Port Said', 'country_code' => 'EG', 'latitude' => 31.2653, 'longitude' => 32.3019, 'type' => 'seaport'],
            ['name' => 'Port of Tripoli', 'country_code' => 'LY', 'latitude' => 32.8872, 'longitude' => 13.1913, 'type' => 'seaport'],
            ['name' => 'Port of Benghazi', 'country_code' => 'LY', 'latitude' => 32.1167, 'longitude' => 20.0667, 'type' => 'seaport'],
            ['name' => 'Port of Tunis', 'country_code' => 'TN', 'latitude' => 36.8189, 'longitude' => 10.1658, 'type' => 'seaport'],
            ['name' => 'Port of Algiers', 'country_code' => 'DZ', 'latitude' => 36.7538, 'longitude' => 3.0588, 'type' => 'seaport'],
            ['name' => 'Port of Oran', 'country_code' => 'DZ', 'latitude' => 35.6969, 'longitude' => -0.6331, 'type' => 'seaport'],
            ['name' => 'Port of Casablanca', 'country_code' => 'MA', 'latitude' => 33.5883, 'longitude' => -7.6114, 'type' => 'seaport'],
            ['name' => 'Port of Tangier', 'country_code' => 'MA', 'latitude' => 35.7595, 'longitude' => -5.8340, 'type' => 'seaport'],
            
            // ============ AFRIKA - SOUTH ============
            ['name' => 'Port of Cape Town', 'country_code' => 'ZA', 'latitude' => -33.9249, 'longitude' => 18.4241, 'type' => 'seaport'],
            ['name' => 'Port of Durban', 'country_code' => 'ZA', 'latitude' => -29.8587, 'longitude' => 31.0218, 'type' => 'seaport'],
            ['name' => 'Port Elizabeth', 'country_code' => 'ZA', 'latitude' => -33.9608, 'longitude' => 25.6022, 'type' => 'seaport'],
            ['name' => 'Port of Luanda', 'country_code' => 'AO', 'latitude' => -8.8383, 'longitude' => 13.2344, 'type' => 'seaport'],
            ['name' => 'Port of Walvis Bay', 'country_code' => 'NA', 'latitude' => -22.9576, 'longitude' => 14.5053, 'type' => 'seaport'],
            
            // ============ SOUTH AMERICA - ATLANTIC COAST ============
            ['name' => 'Port of Santos', 'country_code' => 'BR', 'latitude' => -23.9618, 'longitude' => -46.3322, 'type' => 'seaport'],
            ['name' => 'Port of Rio de Janeiro', 'country_code' => 'BR', 'latitude' => -22.9068, 'longitude' => -43.1729, 'type' => 'seaport'],
            ['name' => 'Port of Salvador', 'country_code' => 'BR', 'latitude' => -12.9714, 'longitude' => -38.5014, 'type' => 'seaport'],
            ['name' => 'Port of Recife', 'country_code' => 'BR', 'latitude' => -8.0476, 'longitude' => -34.8770, 'type' => 'seaport'],
            ['name' => 'Port of Fortaleza', 'country_code' => 'BR', 'latitude' => -3.7172, 'longitude' => -38.5433, 'type' => 'seaport'],
            ['name' => 'Port of Belém', 'country_code' => 'BR', 'latitude' => -1.4558, 'longitude' => -48.5039, 'type' => 'seaport'],
            ['name' => 'Port of Manaus', 'country_code' => 'BR', 'latitude' => -3.1190, 'longitude' => -60.0217, 'type' => 'river'],
            ['name' => 'Port of Buenos Aires', 'country_code' => 'AR', 'latitude' => -34.6037, 'longitude' => -58.3816, 'type' => 'seaport'],
            ['name' => 'Port of Montevideo', 'country_code' => 'UY', 'latitude' => -34.9011, 'longitude' => -56.1645, 'type' => 'seaport'],
            ['name' => 'Port of Asunción', 'country_code' => 'PY', 'latitude' => -25.2637, 'longitude' => -57.5759, 'type' => 'river'],
            ['name' => 'Port of Cayenne', 'country_code' => 'GF', 'latitude' => 4.9333, 'longitude' => -52.3333, 'type' => 'seaport'],
            ['name' => 'Port of Paramaribo', 'country_code' => 'SR', 'latitude' => 5.8520, 'longitude' => -55.2038, 'type' => 'seaport'],
            ['name' => 'Port of Georgetown', 'country_code' => 'GY', 'latitude' => 6.8013, 'longitude' => -58.1551, 'type' => 'seaport'],
            
            // ============ SOUTH AMERICA - PACIFIC COAST ============
            ['name' => 'Port of Valparaíso', 'country_code' => 'CL', 'latitude' => -33.0472, 'longitude' => -71.6127, 'type' => 'seaport'],
            ['name' => 'Port of San Antonio', 'country_code' => 'CL', 'latitude' => -33.5933, 'longitude' => -71.6127, 'type' => 'seaport'],
            ['name' => 'Port of Callao', 'country_code' => 'PE', 'latitude' => -12.0464, 'longitude' => -77.1028, 'type' => 'seaport'],
            ['name' => 'Port of Guayaquil', 'country_code' => 'EC', 'latitude' => -2.1894, 'longitude' => -79.8883, 'type' => 'seaport'],
            ['name' => 'Port of Buenaventura', 'country_code' => 'CO', 'latitude' => 3.8801, 'longitude' => -77.0318, 'type' => 'seaport'],
            
            // ============ SOUTH AMERICA - CARIBBEAN COAST ============
            ['name' => 'Port of Cartagena', 'country_code' => 'CO', 'latitude' => 10.3910, 'longitude' => -75.4794, 'type' => 'seaport'],
            ['name' => 'Port of Barranquilla', 'country_code' => 'CO', 'latitude' => 10.9639, 'longitude' => -74.7964, 'type' => 'seaport'],
            ['name' => 'Port of Maracaibo', 'country_code' => 'VE', 'latitude' => 10.6316, 'longitude' => -71.6410, 'type' => 'seaport'],
            ['name' => 'Port of La Guaira', 'country_code' => 'VE', 'latitude' => 10.6017, 'longitude' => -66.9324, 'type' => 'seaport'],
            
            // ============ MIDDLE EAST ============
            ['name' => 'Port of Jeddah', 'country_code' => 'SA', 'latitude' => 21.5433, 'longitude' => 39.1728, 'type' => 'seaport'],
            ['name' => 'Port of Dammam', 'country_code' => 'SA', 'latitude' => 26.4207, 'longitude' => 50.0888, 'type' => 'seaport'],
            ['name' => 'Port of Yanbu', 'country_code' => 'SA', 'latitude' => 24.0889, 'longitude' => 38.0617, 'type' => 'seaport'],
            ['name' => 'Port of Dubai', 'country_code' => 'AE', 'latitude' => 25.2048, 'longitude' => 55.2708, 'type' => 'seaport'],
            ['name' => 'Port of Abu Dhabi', 'country_code' => 'AE', 'latitude' => 24.4539, 'longitude' => 54.3773, 'type' => 'seaport'],
            ['name' => 'Port of Sharjah', 'country_code' => 'AE', 'latitude' => 25.3463, 'longitude' => 55.4209, 'type' => 'seaport'],
            ['name' => 'Port of Muscat', 'country_code' => 'OM', 'latitude' => 23.6139, 'longitude' => 58.5430, 'type' => 'seaport'],
            ['name' => 'Port of Salalah', 'country_code' => 'OM', 'latitude' => 16.9392, 'longitude' => 54.0064, 'type' => 'seaport'],
            ['name' => 'Port of Doha', 'country_code' => 'QA', 'latitude' => 25.2854, 'longitude' => 51.5310, 'type' => 'seaport'],
            ['name' => 'Port of Manama', 'country_code' => 'BH', 'latitude' => 26.2285, 'longitude' => 50.5860, 'type' => 'seaport'],
            ['name' => 'Port of Kuwait', 'country_code' => 'KW', 'latitude' => 29.3759, 'longitude' => 47.9774, 'type' => 'seaport'],
            ['name' => 'Port of Aqaba', 'country_code' => 'JO', 'latitude' => 29.5267, 'longitude' => 35.0081, 'type' => 'seaport'],
            ['name' => 'Port of Beirut', 'country_code' => 'LB', 'latitude' => 33.9010, 'longitude' => 35.5053, 'type' => 'seaport'],
            ['name' => 'Port of Latakia', 'country_code' => 'SY', 'latitude' => 35.5213, 'longitude' => 35.7818, 'type' => 'seaport'],
            ['name' => 'Port of Haifa', 'country_code' => 'IL', 'latitude' => 32.8191, 'longitude' => 34.9983, 'type' => 'seaport'],
            ['name' => 'Port of Ashdod', 'country_code' => 'IL', 'latitude' => 31.8044, 'longitude' => 34.6553, 'type' => 'seaport'],
            
            // ============ CARIBBEAN ============
            ['name' => 'Port of Kingston', 'country_code' => 'JM', 'latitude' => 17.9714, 'longitude' => -76.7931, 'type' => 'seaport'],
            ['name' => 'Port of Havana', 'country_code' => 'CU', 'latitude' => 23.1136, 'longitude' => -82.3666, 'type' => 'seaport'],
            ['name' => 'Port of Santiago de Cuba', 'country_code' => 'CU', 'latitude' => 20.0241, 'longitude' => -75.8216, 'type' => 'seaport'],
            ['name' => 'Port of Santo Domingo', 'country_code' => 'DO', 'latitude' => 18.4861, 'longitude' => -69.9312, 'type' => 'seaport'],
            ['name' => 'Port-au-Prince', 'country_code' => 'HT', 'latitude' => 18.5944, 'longitude' => -72.3074, 'type' => 'seaport'],
            ['name' => 'Port of San Juan', 'country_code' => 'PR', 'latitude' => 18.4655, 'longitude' => -66.1057, 'type' => 'seaport'],
            ['name' => 'Port of Bridgetown', 'country_code' => 'BB', 'latitude' => 13.0969, 'longitude' => -59.6145, 'type' => 'seaport'],
            ['name' => 'Port of Port of Spain', 'country_code' => 'TT', 'latitude' => 10.6596, 'longitude' => -61.5097, 'type' => 'seaport'],
            ['name' => 'Port of Willemstad', 'country_code' => 'CW', 'latitude' => 12.1084, 'longitude' => -68.9335, 'type' => 'seaport'],
            ['name' => 'Port of Oranjestad', 'country_code' => 'AW', 'latitude' => 12.5186, 'longitude' => -70.0358, 'type' => 'seaport'],
            ['name' => 'Nassau Port', 'country_code' => 'BS', 'latitude' => 25.0443, 'longitude' => -77.3504, 'type' => 'seaport'],
            ['name' => 'Freeport Harbour', 'country_code' => 'BS', 'latitude' => 26.5333, 'longitude' => -78.6958, 'type' => 'seaport'],
            
            // ============ PACIFIC ISLANDS ============
            ['name' => 'Port of Suva', 'country_code' => 'FJ', 'latitude' => -18.1416, 'longitude' => 178.4419, 'type' => 'seaport'],
            ['name' => 'Port of Apia', 'country_code' => 'WS', 'latitude' => -13.8333, 'longitude' => -171.7667, 'type' => 'seaport'],
            ['name' => 'Port of Nuku\'alofa', 'country_code' => 'TO', 'latitude' => -21.1393, 'longitude' => -175.2164, 'type' => 'seaport'],
            ['name' => 'Port Vila', 'country_code' => 'VU', 'latitude' => -17.7333, 'longitude' => 168.3167, 'type' => 'seaport'],
            ['name' => 'Honiara Port', 'country_code' => 'SB', 'latitude' => -9.4333, 'longitude' => 159.9500, 'type' => 'seaport'],
            ['name' => 'Port Moresby', 'country_code' => 'PG', 'latitude' => -9.4438, 'longitude' => 147.1803, 'type' => 'seaport'],
            ['name' => 'Papeete Port', 'country_code' => 'PF', 'latitude' => -17.5350, 'longitude' => -149.5696, 'type' => 'seaport'],
            ['name' => 'Nouméa Port', 'country_code' => 'NC', 'latitude' => -22.2758, 'longitude' => 166.4572, 'type' => 'seaport'],
            ['name' => 'Pago Pago', 'country_code' => 'AS', 'latitude' => -14.2756, 'longitude' => -170.7022, 'type' => 'seaport'],
            ['name' => 'Majuro Port', 'country_code' => 'MH', 'latitude' => 7.0897, 'longitude' => 171.3803, 'type' => 'seaport'],
            ['name' => 'Tarawa Port', 'country_code' => 'KI', 'latitude' => 1.3382, 'longitude' => 173.0176, 'type' => 'seaport'],
            ['name' => 'Funafuti Port', 'country_code' => 'TV', 'latitude' => -8.5167, 'longitude' => 179.2167, 'type' => 'seaport'],
            ['name' => 'Yaren Port', 'country_code' => 'NR', 'latitude' => -0.5477, 'longitude' => 166.9209, 'type' => 'seaport'],
            ['name' => 'Koror Port', 'country_code' => 'PW', 'latitude' => 7.3419, 'longitude' => 134.4789, 'type' => 'seaport'],
            
            // ============ MEDITERRANEAN ============
            ['name' => 'Port of Piraeus', 'country_code' => 'GR', 'latitude' => 37.9364, 'longitude' => 23.6473, 'type' => 'seaport'],
            ['name' => 'Port of Thessaloniki', 'country_code' => 'GR', 'latitude' => 40.6401, 'longitude' => 22.9444, 'type' => 'seaport'],
            ['name' => 'Port of Heraklion', 'country_code' => 'GR', 'latitude' => 35.3387, 'longitude' => 25.1442, 'type' => 'seaport'],
            ['name' => 'Port of Rhodes', 'country_code' => 'GR', 'latitude' => 36.4341, 'longitude' => 28.2176, 'type' => 'seaport'],
            ['name' => 'Port of Limassol', 'country_code' => 'CY', 'latitude' => 34.6720, 'longitude' => 33.0378, 'type' => 'seaport'],
            ['name' => 'Port of Valletta', 'country_code' => 'MT', 'latitude' => 35.8989, 'longitude' => 14.5146, 'type' => 'seaport'],
            ['name' => 'Port of Marseille', 'country_code' => 'FR', 'latitude' => 43.2965, 'longitude' => 5.3698, 'type' => 'seaport'],
            ['name' => 'Port of Genoa', 'country_code' => 'IT', 'latitude' => 44.4056, 'longitude' => 8.9463, 'type' => 'seaport'],
            ['name' => 'Port of Naples', 'country_code' => 'IT', 'latitude' => 40.8518, 'longitude' => 14.2681, 'type' => 'seaport'],
            ['name' => 'Port of Venice', 'country_code' => 'IT', 'latitude' => 45.4408, 'longitude' => 12.3155, 'type' => 'seaport'],
            ['name' => 'Port of Palermo', 'country_code' => 'IT', 'latitude' => 38.1157, 'longitude' => 13.3615, 'type' => 'seaport'],
            ['name' => 'Port of Barcelona', 'country_code' => 'ES', 'latitude' => 41.3851, 'longitude' => 2.1734, 'type' => 'seaport'],
            ['name' => 'Port of Valencia', 'country_code' => 'ES', 'latitude' => 39.4699, 'longitude' => -0.3763, 'type' => 'seaport'],
            ['name' => 'Port of Izmir', 'country_code' => 'TR', 'latitude' => 38.4237, 'longitude' => 27.1428, 'type' => 'seaport'],
            ['name' => 'Port of Antalya', 'country_code' => 'TR', 'latitude' => 36.8841, 'longitude' => 30.7056, 'type' => 'seaport'],
            ['name' => 'Port of Mersin', 'country_code' => 'TR', 'latitude' => 36.8121, 'longitude' => 34.6415, 'type' => 'seaport'],
            
            // ============ SOUTHEAST ASIA - INDONESIA ARCHIPELAGO ============
            ['name' => 'Port of Surabaya', 'country_code' => 'ID', 'latitude' => -7.2504, 'longitude' => 112.7688, 'type' => 'seaport'],
            ['name' => 'Port of Medan', 'country_code' => 'ID', 'latitude' => 3.5952, 'longitude' => 98.6722, 'type' => 'seaport'],
            ['name' => 'Port of Makassar', 'country_code' => 'ID', 'latitude' => -5.1477, 'longitude' => 119.4327, 'type' => 'seaport'],
            ['name' => 'Port of Balikpapan', 'country_code' => 'ID', 'latitude' => -1.2635, 'longitude' => 116.8289, 'type' => 'seaport'],
            ['name' => 'Port of Palembang', 'country_code' => 'ID', 'latitude' => -2.9761, 'longitude' => 104.7754, 'type' => 'seaport'],
            ['name' => 'Port of Banjarmasin', 'country_code' => 'ID', 'latitude' => -3.3186, 'longitude' => 114.5942, 'type' => 'seaport'],
            ['name' => 'Port of Jayapura', 'country_code' => 'ID', 'latitude' => -2.5333, 'longitude' => 140.7167, 'type' => 'seaport'],
            ['name' => 'Port of Ambon', 'country_code' => 'ID', 'latitude' => -3.6954, 'longitude' => 128.1814, 'type' => 'seaport'],
            ['name' => 'Port of Manado', 'country_code' => 'ID', 'latitude' => 1.4748, 'longitude' => 124.8421, 'type' => 'seaport'],
            ['name' => 'Port of Pontianak', 'country_code' => 'ID', 'latitude' => -0.0263, 'longitude' => 109.3425, 'type' => 'seaport'],
            
            // ============ SOUTHEAST ASIA - PHILIPPINES ============
            ['name' => 'Port of Cebu', 'country_code' => 'PH', 'latitude' => 10.3157, 'longitude' => 123.8854, 'type' => 'seaport'],
            ['name' => 'Port of Davao', 'country_code' => 'PH', 'latitude' => 7.0731, 'longitude' => 125.6128, 'type' => 'seaport'],
            ['name' => 'Port of Iloilo', 'country_code' => 'PH', 'latitude' => 10.7202, 'longitude' => 122.5621, 'type' => 'seaport'],
            ['name' => 'Port of Cagayan de Oro', 'country_code' => 'PH', 'latitude' => 8.4542, 'longitude' => 124.6319, 'type' => 'seaport'],
            ['name' => 'Port of Zamboanga', 'country_code' => 'PH', 'latitude' => 6.9214, 'longitude' => 122.0790, 'type' => 'seaport'],
            ['name' => 'Port of Batangas', 'country_code' => 'PH', 'latitude' => 13.7565, 'longitude' => 121.0583, 'type' => 'seaport'],
            ['name' => 'Port of Subic Bay', 'country_code' => 'PH', 'latitude' => 14.8203, 'longitude' => 120.2721, 'type' => 'seaport'],
            
            // ============ SOUTHEAST ASIA - VIETNAM & THAILAND ============
            ['name' => 'Port of Haiphong', 'country_code' => 'VN', 'latitude' => 20.8449, 'longitude' => 106.6881, 'type' => 'seaport'],
            ['name' => 'Port of Da Nang', 'country_code' => 'VN', 'latitude' => 16.0678, 'longitude' => 108.2208, 'type' => 'seaport'],
            ['name' => 'Port of Vung Tau', 'country_code' => 'VN', 'latitude' => 10.3460, 'longitude' => 107.0843, 'type' => 'seaport'],
            ['name' => 'Port of Quy Nhon', 'country_code' => 'VN', 'latitude' => 13.7830, 'longitude' => 109.2196, 'type' => 'seaport'],
            ['name' => 'Port of Laem Chabang', 'country_code' => 'TH', 'latitude' => 13.0825, 'longitude' => 100.8831, 'type' => 'seaport'],
            ['name' => 'Port of Bangkok', 'country_code' => 'TH', 'latitude' => 13.7563, 'longitude' => 100.5018, 'type' => 'seaport'],
            ['name' => 'Port of Phuket', 'country_code' => 'TH', 'latitude' => 7.8804, 'longitude' => 98.3923, 'type' => 'seaport'],
            ['name' => 'Port of Songkhla', 'country_code' => 'TH', 'latitude' => 7.1756, 'longitude' => 100.6145, 'type' => 'seaport'],
            
            // ============ SOUTHEAST ASIA - MALAYSIA ============
            ['name' => 'Port of Penang', 'country_code' => 'MY', 'latitude' => 5.4141, 'longitude' => 100.3288, 'type' => 'seaport'],
            ['name' => 'Port Klang', 'country_code' => 'MY', 'latitude' => 3.0048, 'longitude' => 101.3901, 'type' => 'seaport'],
            ['name' => 'Port of Johor', 'country_code' => 'MY', 'latitude' => 1.4655, 'longitude' => 103.7578, 'type' => 'seaport'],
            ['name' => 'Port of Kuching', 'country_code' => 'MY', 'latitude' => 1.5535, 'longitude' => 110.3593, 'type' => 'seaport'],
            ['name' => 'Port of Kota Kinabalu', 'country_code' => 'MY', 'latitude' => 5.9804, 'longitude' => 116.0735, 'type' => 'seaport'],
            
            // ============ CENTRAL ASIA & INTERIOR ============
            ['name' => 'Port of Astrakhan', 'country_code' => 'RU', 'latitude' => 46.3497, 'longitude' => 48.0408, 'type' => 'river'],
            ['name' => 'Port of Volgograd', 'country_code' => 'RU', 'latitude' => 48.7080, 'longitude' => 44.5133, 'type' => 'river'],
            ['name' => 'Port of Kazan', 'country_code' => 'RU', 'latitude' => 55.7887, 'longitude' => 49.1221, 'type' => 'river'],
            ['name' => 'Port of Nizhny Novgorod', 'country_code' => 'RU', 'latitude' => 56.3269, 'longitude' => 44.0075, 'type' => 'river'],
            ['name' => 'Port of Aktau', 'country_code' => 'KZ', 'latitude' => 43.6500, 'longitude' => 51.1600, 'type' => 'seaport'],
            ['name' => 'Port of Turkmenbashi', 'country_code' => 'TM', 'latitude' => 40.0229, 'longitude' => 52.9553, 'type' => 'seaport'],
            ['name' => 'Port of Baku', 'country_code' => 'AZ', 'latitude' => 40.4093, 'longitude' => 49.8671, 'type' => 'seaport'],
            
            // ============ INDIAN OCEAN ============
            ['name' => 'Port Louis', 'country_code' => 'MU', 'latitude' => -20.1609, 'longitude' => 57.5012, 'type' => 'seaport'],
            ['name' => 'Port of Victoria', 'country_code' => 'SC', 'latitude' => -4.6167, 'longitude' => 55.4500, 'type' => 'seaport'],
            ['name' => 'Port of Moroni', 'country_code' => 'KM', 'latitude' => -11.7172, 'longitude' => 43.2473, 'type' => 'seaport'],
            ['name' => 'Port of Antsiranana', 'country_code' => 'MG', 'latitude' => -12.2787, 'longitude' => 49.2917, 'type' => 'seaport'],
            ['name' => 'Port of Toamasina', 'country_code' => 'MG', 'latitude' => -18.1443, 'longitude' => 49.4021, 'type' => 'seaport'],
            ['name' => 'Port of Malé', 'country_code' => 'MV', 'latitude' => 4.1755, 'longitude' => 73.5093, 'type' => 'seaport'],
            
            // ============ CENTRAL AMERICA ============
            ['name' => 'Port of Colón', 'country_code' => 'PA', 'latitude' => 9.3592, 'longitude' => -79.9009, 'type' => 'seaport'],
            ['name' => 'Port of Balboa', 'country_code' => 'PA', 'latitude' => 8.9500, 'longitude' => -79.5667, 'type' => 'seaport'],
            ['name' => 'Port of Puerto Limón', 'country_code' => 'CR', 'latitude' => 10.0000, 'longitude' => -83.0333, 'type' => 'seaport'],
            ['name' => 'Port of Corinto', 'country_code' => 'NI', 'latitude' => 12.4830, 'longitude' => -87.1672, 'type' => 'seaport'],
            ['name' => 'Port of La Unión', 'country_code' => 'SV', 'latitude' => 13.3369, 'longitude' => -87.8439, 'type' => 'seaport'],
            ['name' => 'Port of Puerto Cortés', 'country_code' => 'HN', 'latitude' => 15.8270, 'longitude' => -87.9464, 'type' => 'seaport'],
            ['name' => 'Port of Santo Tomás de Castilla', 'country_code' => 'GT', 'latitude' => 15.6833, 'longitude' => -88.6167, 'type' => 'seaport'],
            ['name' => 'Port of Veracruz', 'country_code' => 'MX', 'latitude' => 19.1738, 'longitude' => -96.1342, 'type' => 'seaport'],
            ['name' => 'Port of Manzanillo', 'country_code' => 'MX', 'latitude' => 19.0543, 'longitude' => -104.3188, 'type' => 'seaport'],
            ['name' => 'Port of Lázaro Cárdenas', 'country_code' => 'MX', 'latitude' => 17.9567, 'longitude' => -102.1761, 'type' => 'seaport'],
            
            // ============ NORTH AMERICA - US ADDITIONAL ============
            ['name' => 'Port of Miami', 'country_code' => 'US', 'latitude' => 25.7743, 'longitude' => -80.1937, 'type' => 'seaport'],
            ['name' => 'Port of Savannah', 'country_code' => 'US', 'latitude' => 32.0809, 'longitude' => -81.0912, 'type' => 'seaport'],
            ['name' => 'Port of Charleston', 'country_code' => 'US', 'latitude' => 32.7765, 'longitude' => -79.9311, 'type' => 'seaport'],
            ['name' => 'Port of New Orleans', 'country_code' => 'US', 'latitude' => 29.9511, 'longitude' => -90.0715, 'type' => 'seaport'],
            ['name' => 'Port of Houston', 'country_code' => 'US', 'latitude' => 29.7604, 'longitude' => -95.3698, 'type' => 'seaport'],
            ['name' => 'Port of Oakland', 'country_code' => 'US', 'latitude' => 37.8044, 'longitude' => -122.2711, 'type' => 'seaport'],
            ['name' => 'Port of Tacoma', 'country_code' => 'US', 'latitude' => 47.2529, 'longitude' => -122.4443, 'type' => 'seaport'],
            ['name' => 'Port of Anchorage', 'country_code' => 'US', 'latitude' => 61.2181, 'longitude' => -149.9003, 'type' => 'seaport'],
            ['name' => 'Port of Honolulu', 'country_code' => 'US', 'latitude' => 21.3099, 'longitude' => -157.8581, 'type' => 'seaport'],
            
            // ============ AUSTRALIA ADDITIONAL ============
            ['name' => 'Port of Fremantle', 'country_code' => 'AU', 'latitude' => -32.0569, 'longitude' => 115.7439, 'type' => 'seaport'],
            ['name' => 'Port of Adelaide', 'country_code' => 'AU', 'latitude' => -34.9285, 'longitude' => 138.6007, 'type' => 'seaport'],
            ['name' => 'Port of Darwin', 'country_code' => 'AU', 'latitude' => -12.4634, 'longitude' => 130.8456, 'type' => 'seaport'],
            ['name' => 'Port of Townsville', 'country_code' => 'AU', 'latitude' => -19.2590, 'longitude' => 146.8169, 'type' => 'seaport'],
            ['name' => 'Port of Cairns', 'country_code' => 'AU', 'latitude' => -16.9186, 'longitude' => 145.7781, 'type' => 'seaport'],
            ['name' => 'Port of Hobart', 'country_code' => 'AU', 'latitude' => -42.8821, 'longitude' => 147.3272, 'type' => 'seaport'],
            
            // ============ NEW ZEALAND ADDITIONAL ============
            ['name' => 'Port of Wellington', 'country_code' => 'NZ', 'latitude' => -41.2865, 'longitude' => 174.7762, 'type' => 'seaport'],
            ['name' => 'Port of Christchurch', 'country_code' => 'NZ', 'latitude' => -43.5321, 'longitude' => 172.6362, 'type' => 'seaport'],
            ['name' => 'Port of Dunedin', 'country_code' => 'NZ', 'latitude' => -45.8788, 'longitude' => 170.5028, 'type' => 'seaport'],
            ['name' => 'Port of Napier', 'country_code' => 'NZ', 'latitude' => -39.4928, 'longitude' => 176.9120, 'type' => 'seaport'],
            
            // ============ INDIAN SUBCONTINENT ADDITIONAL ============
            ['name' => 'Port of Visakhapatnam', 'country_code' => 'IN', 'latitude' => 17.6868, 'longitude' => 83.2185, 'type' => 'seaport'],
            ['name' => 'Port of Paradip', 'country_code' => 'IN', 'latitude' => 20.3167, 'longitude' => 86.6100, 'type' => 'seaport'],
            ['name' => 'Port of Tuticorin', 'country_code' => 'IN', 'latitude' => 8.7642, 'longitude' => 78.1348, 'type' => 'seaport'],
            ['name' => 'Port of Kandla', 'country_code' => 'IN', 'latitude' => 23.0333, 'longitude' => 70.2167, 'type' => 'seaport'],
            ['name' => 'Port of Chittagong', 'country_code' => 'BD', 'latitude' => 22.3569, 'longitude' => 91.7832, 'type' => 'seaport'],
            ['name' => 'Port of Colombo', 'country_code' => 'LK', 'latitude' => 6.9271, 'longitude' => 79.8612, 'type' => 'seaport'],
            ['name' => 'Port of Galle', 'country_code' => 'LK', 'latitude' => 6.0535, 'longitude' => 80.2210, 'type' => 'seaport'],
            ['name' => 'Port of Karachi', 'country_code' => 'PK', 'latitude' => 24.8607, 'longitude' => 67.0011, 'type' => 'seaport'],
            ['name' => 'Port of Gwadar', 'country_code' => 'PK', 'latitude' => 25.1264, 'longitude' => 62.3250, 'type' => 'seaport'],
            
            // ============ EAST ASIA ADDITIONAL ============
            ['name' => 'Port of Tianjin', 'country_code' => 'CN', 'latitude' => 39.0842, 'longitude' => 117.2010, 'type' => 'seaport'],
            ['name' => 'Port of Qingdao', 'country_code' => 'CN', 'latitude' => 36.0671, 'longitude' => 120.3826, 'type' => 'seaport'],
            ['name' => 'Port of Guangzhou', 'country_code' => 'CN', 'latitude' => 23.1291, 'longitude' => 113.2644, 'type' => 'seaport'],
            ['name' => 'Port of Xiamen', 'country_code' => 'CN', 'latitude' => 24.4798, 'longitude' => 118.0894, 'type' => 'seaport'],
            ['name' => 'Port of Dalian', 'country_code' => 'CN', 'latitude' => 38.9140, 'longitude' => 121.6147, 'type' => 'seaport'],
            ['name' => 'Port of Yokohama', 'country_code' => 'JP', 'latitude' => 35.4437, 'longitude' => 139.6380, 'type' => 'seaport'],
            ['name' => 'Port of Osaka', 'country_code' => 'JP', 'latitude' => 34.6937, 'longitude' => 135.5023, 'type' => 'seaport'],
            ['name' => 'Port of Kobe', 'country_code' => 'JP', 'latitude' => 34.6901, 'longitude' => 135.1955, 'type' => 'seaport'],
            ['name' => 'Port of Nagoya', 'country_code' => 'JP', 'latitude' => 35.1815, 'longitude' => 136.9066, 'type' => 'seaport'],
            ['name' => 'Port of Busan', 'country_code' => 'KR', 'latitude' => 35.1796, 'longitude' => 129.0756, 'type' => 'seaport'],
            ['name' => 'Port of Incheon', 'country_code' => 'KR', 'latitude' => 37.4563, 'longitude' => 126.7052, 'type' => 'seaport'],
        ];
    }
}
