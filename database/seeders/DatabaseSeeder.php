<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Utama untuk Login Demo Kuliah
        DB::table('users')->insertOrIgnore([
            'name' => 'Admin Logistik',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Sampel Negara Utama Sesuai Cakupan Studi Kasus Global
        $countries = [
            ['code' => 'ID', 'name' => 'Indonesia', 'currency_code' => 'IDR', 'region' => 'Asia', 'languages' => 'Indonesian'],
            ['code' => 'CN', 'name' => 'China', 'currency_code' => 'CNY', 'region' => 'Asia', 'languages' => 'Chinese'],
            ['code' => 'US', 'name' => 'United States', 'currency_code' => 'USD', 'region' => 'Americas', 'languages' => 'English'],
            ['code' => 'DE', 'name' => 'Germany', 'currency_code' => 'EUR', 'region' => 'Europe', 'languages' => 'German'],
            ['code' => 'JP', 'name' => 'Japan', 'currency_code' => 'JPY', 'region' => 'Asia', 'languages' => 'Japanese'],
            ['code' => 'SG', 'name' => 'Singapore', 'currency_code' => 'SGD', 'region' => 'Asia', 'languages' => 'English, Malay, Mandarin, Tamil'],
        ];

        foreach ($countries as $country) {
            DB::table('countries')->insertOrIgnore($country);
        }

        // 3. Kamus Kata Positif untuk Fitur AI Lexicon Sentiment Analysis
        $positiveWords = ['growth', 'increase', 'profit', 'stable', 'improve', 'strengthen', 'recovery', 'surplus', 'boost', 'positive'];
        foreach ($positiveWords as $word) {
            DB::table('sentiment_dictionaries')->insertOrIgnore([
                'word' => $word,
                'type' => 'positive',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Kamus Kata Negatif untuk Fitur AI Lexicon Sentiment Analysis
        $negativeWords = ['war', 'crisis', 'inflation', 'delay', 'disaster', 'decrease', 'drop', 'conflict', 'risk', 'deficit', 'storm', 'shutdown'];
        foreach ($negativeWords as $word) {
            DB::table('sentiment_dictionaries')->insertOrIgnore([
                'word' => $word,
                'type' => 'negative',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 5. Data Koordinat Pelabuhan Utama Dunia untuk Peta Leaflet.js
        $ports = [
            ['port_name' => 'Port of Tanjung Priok', 'country_code' => 'ID', 'latitude' => -6.1023000, 'longitude' => 106.8906000, 'index_number' => 'WPI-49810', 'created_at' => now(), 'updated_at' => now()],
            ['port_name' => 'Port of Shanghai', 'country_code' => 'CN', 'latitude' => 31.2304000, 'longitude' => 121.4737000, 'index_number' => 'WPI-50010', 'created_at' => now(), 'updated_at' => now()],
            ['port_name' => 'Port of Los Angeles', 'country_code' => 'US', 'latitude' => 33.7432000, 'longitude' => -118.2673000, 'index_number' => 'WPI-22440', 'created_at' => now(), 'updated_at' => now()],
            ['port_name' => 'Port of Hamburg', 'country_code' => 'DE', 'latitude' => 53.5458000, 'longitude' => 9.9645000, 'index_number' => 'WPI-12340', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($ports as $port) {
            DB::table('ports')->insertOrIgnore($port);
        }
    }
}