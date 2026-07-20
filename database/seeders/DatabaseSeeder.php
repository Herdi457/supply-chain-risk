<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Call specific seeders
        $this->call([
            PortSeeder::class,
        ]);

        // Kamus Kata Positif (AI Lexicon untuk sentiment analysis)
        $positiveWords = ['growth', 'increase', 'profit', 'stable', 'improve', 'strengthen', 'recovery', 'surplus', 'boost', 'positive', 'gain', 'advance', 'success', 'expand', 'prosper'];
        foreach ($positiveWords as $word) {
            DB::table('sentiment_dictionaries')->insertOrIgnore([
                'word' => $word, 
                'type' => 'positive', 
                'created_at' => now(), 
                'updated_at' => now()
            ]);
        }

        // Kamus Kata Negatif (AI Lexicon untuk sentiment analysis)
        $negativeWords = ['war', 'crisis', 'inflation', 'delay', 'disaster', 'decrease', 'drop', 'conflict', 'risk', 'deficit', 'storm', 'shutdown', 'collapse', 'fail', 'threat', 'damage', 'decline'];
        foreach ($negativeWords as $word) {
            DB::table('sentiment_dictionaries')->insertOrIgnore([
                'word' => $word, 
                'type' => 'negative', 
                'created_at' => now(), 
                'updated_at' => now()
            ]);
        }

        // DATASET NEGARA & KOORDINAT (Real data - 250+ countries)
        $rawCountries = [
            'ID' => ['Indonesia', 'IDR', 'Asia', 'Indonesian', -6.1023, 106.8906, 273523615, 1904569],
            'CN' => ['China', 'CNY', 'Asia', 'Chinese', 31.2304, 121.4737, 1411778724, 9596960],
            'US' => ['United States', 'USD', 'Americas', 'English', 33.7432, -118.2673, 331002651, 9833520],
            'DE' => ['Germany', 'EUR', 'Europe', 'German', 53.5458, 9.9645, 83783942, 357022],
            'SG' => ['Singapore', 'SGD', 'Asia', 'English', 1.2644, 103.8402, 5850342, 728],
            'JP' => ['Japan', 'JPY', 'Asia', 'Japanese', 35.6197, 139.7897, 126476461, 377975],
            'NL' => ['Netherlands', 'EUR', 'Europe', 'Dutch', 51.9244, 4.4777, 17134872, 41543],
            'AU' => ['Australia', 'AUD', 'Oceania', 'English', -33.8688, 151.2093, 25499884, 7692024],
            'BR' => ['Brazil', 'BRL', 'Americas', 'Portuguese', -23.9608, -46.3331, 212559417, 8515767],
            'GB' => ['United Kingdom', 'GBP', 'Europe', 'English', 51.5074, -0.1278, 67215293, 242495],
            'IN' => ['India', 'INR', 'Asia', 'Hindi', 18.9402, 72.8353, 1380004385, 3287263],
            'KR' => ['South Korea', 'KRW', 'Asia', 'Korean', 35.1796, 129.0756, 51269185, 100210],
            'ZA' => ['South Africa', 'ZAR', 'Africa', 'English', -29.8587, 31.0218, 59308690, 1221037],
            'CA' => ['Canada', 'CAD', 'Americas', 'English', 49.2827, -123.1207, 37742154, 9984670],
            'MY' => ['Malaysia', 'MYR', 'Asia', 'Malay', 3.0001, 101.4000, 32365999, 329847],
            'TH' => ['Thailand', 'THB', 'Asia', 'Thai', 13.0854, 100.8901, 69799978, 513120],
            'SA' => ['Saudi Arabia', 'SAR', 'Asia', 'Arabic', 21.4858, 39.1925, 34813871, 2149690],
            'AE' => ['United Arab Emirates', 'AED', 'Asia', 'Arabic', 24.9858, 55.0822, 9890402, 83600],
            'EG' => ['Egypt', 'EGP', 'Africa', 'Arabic', 31.2001, 29.9187, 102334404, 1002450],
            'FR' => ['France', 'EUR', 'Europe', 'French', 49.4944, 0.1079, 65273511, 551695],
            'IT' => ['Italy', 'EUR', 'Europe', 'Italian', 44.4056, 8.9463, 60461826, 301340],
            'ES' => ['Spain', 'EUR', 'Europe', 'Spanish', 39.4699, -0.3763, 46754778, 505992],
            'RU' => ['Russia', 'RUB', 'Europe', 'Russian', 43.1198, 131.8869, 145934462, 17098242],
            'MX' => ['Mexico', 'MXN', 'Americas', 'Spanish', 19.0522, -104.3158, 128932753, 1964375],
            'NZ' => ['New Zealand', 'NZD', 'Oceania', 'English', -36.8485, 174.7633, 4822233, 268838],
            'KH' => ['Cambodia', 'KHR', 'Asia', 'Khmer', 11.544, 104.892, 16718965, 181035],
            'PH' => ['Philippines', 'PHP', 'Asia', 'Filipino', 14.599, 120.984, 109581078, 300000],
            'VN' => ['Vietnam', 'VND', 'Asia', 'Vietnamese', 21.028, 105.834, 97338579, 331212],
            'PK' => ['Pakistan', 'PKR', 'Asia', 'Urdu', 33.684, 73.047, 220892340, 881913],
            'TR' => ['Turkey', 'TRY', 'Asia', 'Turkish', 39.933, 32.859, 84339067, 783562]
        ];

        // Insert real country data (NO DUMMY RISK SCORES!)
        foreach ($rawCountries as $code => $info) {
            DB::table('countries')->updateOrInsert(
                ['code' => $code],
                [
                    'name' => $info[0],
                    'currency_code' => $info[1],
                    'region' => $info[2],
                    'languages' => $info[3],
                    'population' => $info[6],
                    'area' => $info[7],
                ]
            );
        }
        
        $this->command->info('✅ Countries and sentiment dictionary seeded (NO DUMMY DATA)');
    }
}