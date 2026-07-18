<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Port;

class PortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama
        Port::truncate();

        // Data pelabuhan utama dunia (28 pelabuhan)
        $ports = [
            // Asia
            ['port_name' => 'Port of Singapore', 'country_code' => 'SG', 'latitude' => 1.2644, 'longitude' => 103.8220, 'index_number' => 'WPI-SG001'],
            ['port_name' => 'Port of Shanghai', 'country_code' => 'CN', 'latitude' => 31.2304, 'longitude' => 121.4737, 'index_number' => 'WPI-CN001'],
            ['port_name' => 'Port of Hong Kong', 'country_code' => 'HK', 'latitude' => 22.3193, 'longitude' => 114.1694, 'index_number' => 'WPI-HK001'],
            ['port_name' => 'Port of Shenzhen', 'country_code' => 'CN', 'latitude' => 22.5431, 'longitude' => 114.0579, 'index_number' => 'WPI-CN002'],
            ['port_name' => 'Port of Busan', 'country_code' => 'KR', 'latitude' => 35.1796, 'longitude' => 129.0756, 'index_number' => 'WPI-KR001'],
            ['port_name' => 'Port of Ningbo-Zhoushan', 'country_code' => 'CN', 'latitude' => 29.8683, 'longitude' => 121.5440, 'index_number' => 'WPI-CN003'],
            ['port_name' => 'Tanjung Priok Port', 'country_code' => 'ID', 'latitude' => -6.1052, 'longitude' => 106.8818, 'index_number' => 'WPI-ID001'],
            ['port_name' => 'Port of Tokyo', 'country_code' => 'JP', 'latitude' => 35.6532, 'longitude' => 139.7698, 'index_number' => 'WPI-JP001'],
            ['port_name' => 'Port of Yokohama', 'country_code' => 'JP', 'latitude' => 35.4437, 'longitude' => 139.6380, 'index_number' => 'WPI-JP002'],
            ['port_name' => 'Port of Laem Chabang', 'country_code' => 'TH', 'latitude' => 13.0827, 'longitude' => 100.8831, 'index_number' => 'WPI-TH001'],
            
            // Europe
            ['port_name' => 'Port of Rotterdam', 'country_code' => 'NL', 'latitude' => 51.9225, 'longitude' => 4.47917, 'index_number' => 'WPI-NL001'],
            ['port_name' => 'Port of Antwerp', 'country_code' => 'BE', 'latitude' => 51.2194, 'longitude' => 4.4025, 'index_number' => 'WPI-BE001'],
            ['port_name' => 'Port of Hamburg', 'country_code' => 'DE', 'latitude' => 53.5511, 'longitude' => 9.9937, 'index_number' => 'WPI-DE001'],
            ['port_name' => 'Port of Valencia', 'country_code' => 'ES', 'latitude' => 39.4699, 'longitude' => -0.3763, 'index_number' => 'WPI-ES001'],
            ['port_name' => 'Port of Felixstowe', 'country_code' => 'GB', 'latitude' => 51.9614, 'longitude' => 1.3511, 'index_number' => 'WPI-GB001'],
            ['port_name' => 'Port of Le Havre', 'country_code' => 'FR', 'latitude' => 49.4944, 'longitude' => 0.1079, 'index_number' => 'WPI-FR001'],
            
            // Americas
            ['port_name' => 'Port of Los Angeles', 'country_code' => 'US', 'latitude' => 33.7405, 'longitude' => -118.2722, 'index_number' => 'WPI-US001'],
            ['port_name' => 'Port of Long Beach', 'country_code' => 'US', 'latitude' => 33.7701, 'longitude' => -118.1937, 'index_number' => 'WPI-US002'],
            ['port_name' => 'Port of New York', 'country_code' => 'US', 'latitude' => 40.6655, 'longitude' => -74.0581, 'index_number' => 'WPI-US003'],
            ['port_name' => 'Port of Savannah', 'country_code' => 'US', 'latitude' => 32.0835, 'longitude' => -81.0998, 'index_number' => 'WPI-US004'],
            ['port_name' => 'Port of Santos', 'country_code' => 'BR', 'latitude' => -23.9608, 'longitude' => -46.3330, 'index_number' => 'WPI-BR001'],
            ['port_name' => 'Port of Vancouver', 'country_code' => 'CA', 'latitude' => 49.2827, 'longitude' => -123.1207, 'index_number' => 'WPI-CA001'],
            
            // Middle East
            ['port_name' => 'Port of Dubai', 'country_code' => 'AE', 'latitude' => 25.2048, 'longitude' => 55.2708, 'index_number' => 'WPI-AE001'],
            ['port_name' => 'Port of Jebel Ali', 'country_code' => 'AE', 'latitude' => 25.0118, 'longitude' => 55.0568, 'index_number' => 'WPI-AE002'],
            
            // Oceania
            ['port_name' => 'Port of Melbourne', 'country_code' => 'AU', 'latitude' => -37.8406, 'longitude' => 144.9306, 'index_number' => 'WPI-AU001'],
            ['port_name' => 'Port of Sydney', 'country_code' => 'AU', 'latitude' => -33.8688, 'longitude' => 151.2093, 'index_number' => 'WPI-AU002'],
            
            // Africa
            ['port_name' => 'Port of Durban', 'country_code' => 'ZA', 'latitude' => -29.8587, 'longitude' => 31.0218, 'index_number' => 'WPI-ZA001'],
            ['port_name' => 'Port Said', 'country_code' => 'EG', 'latitude' => 31.2653, 'longitude' => 32.3019, 'index_number' => 'WPI-EG001'],
        ];

        foreach ($ports as $port) {
            Port::create($port);
        }

        $this->command->info('✅ ' . count($ports) . ' pelabuhan berhasil di-seed!');
    }
}
