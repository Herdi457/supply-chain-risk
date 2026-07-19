<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Country;
use App\Models\Port;
use Illuminate\Support\Facades\DB;

class GeneratePortsForAllCountries extends Command
{
    protected $signature = 'ports:generate-all';
    protected $description = 'Generate main port for all countries based on capital/major city coordinates';

    public function handle()
    {
        $this->info('Generating ports for all countries...');
        
        // Get all countries
        $countries = Country::all();
        $generated = 0;
        $skipped = 0;
        
        foreach ($countries as $country) {
            // Check if port already exists
            $existingPort = Port::where('country_code', $country->code)->first();
            
            if ($existingPort) {
                $skipped++;
                continue;
            }
            
            // Get coordinates from existing ports table or use default based on region
            $coordinates = $this->getCountryCoordinates($country);
            
            // Create port
            Port::create([
                'port_name' => 'Main Port of ' . $country->name,
                'country_code' => $country->code,
                'latitude' => $coordinates['lat'],
                'longitude' => $coordinates['lng'],
                'index_number' => 'WPI-' . $country->code . '-001',
            ]);
            
            $generated++;
        }
        
        $this->info("✅ Generated {$generated} new ports");
        $this->info("⏭️  Skipped {$skipped} existing ports");
        $this->info("📊 Total ports now: " . Port::count());
        
        return 0;
    }
    
    private function getCountryCoordinates($country)
    {
        // Try to get from REST Countries API cache or use approximations
        // Based on capital cities or major economic centers
        
        $coordinates = [
            // Europe
            'AL' => ['lat' => 41.3275, 'lng' => 19.8187],  // Albania - Tirana
            'AD' => ['lat' => 42.5063, 'lng' => 1.5218],   // Andorra
            'AM' => ['lat' => 40.1792, 'lng' => 44.4991],  // Armenia
            'AT' => ['lat' => 48.2082, 'lng' => 16.3738],  // Austria - Vienna
            'AZ' => ['lat' => 40.4093, 'lng' => 49.8671],  // Azerbaijan - Baku
            'BY' => ['lat' => 53.9045, 'lng' => 27.5615],  // Belarus
            'BE' => ['lat' => 51.2194, 'lng' => 4.4025],   // Belgium - Antwerp
            'BA' => ['lat' => 43.8563, 'lng' => 18.4131],  // Bosnia
            'BG' => ['lat' => 42.6977, 'lng' => 23.3219],  // Bulgaria
            'HR' => ['lat' => 45.8150, 'lng' => 15.9819],  // Croatia
            'CY' => ['lat' => 34.9174, 'lng' => 33.6239],  // Cyprus
            'CZ' => ['lat' => 50.0755, 'lng' => 14.4378],  // Czech Republic
            'DK' => ['lat' => 55.6761, 'lng' => 12.5683],  // Denmark
            'EE' => ['lat' => 59.4370, 'lng' => 24.7536],  // Estonia
            'FI' => ['lat' => 60.1699, 'lng' => 24.9384],  // Finland
            'FR' => ['lat' => 49.4944, 'lng' => 0.1079],   // France - Le Havre
            'GE' => ['lat' => 41.7151, 'lng' => 44.8271],  // Georgia
            'DE' => ['lat' => 53.5511, 'lng' => 9.9937],   // Germany - Hamburg
            'GR' => ['lat' => 37.9838, 'lng' => 23.7275],  // Greece
            'HU' => ['lat' => 47.4979, 'lng' => 19.0402],  // Hungary
            'IS' => ['lat' => 64.1466, 'lng' => -21.9426], // Iceland
            'IE' => ['lat' => 53.3498, 'lng' => -6.2603],  // Ireland
            'IT' => ['lat' => 44.4056, 'lng' => 8.9463],   // Italy - Genoa
            'XK' => ['lat' => 42.6629, 'lng' => 21.1655],  // Kosovo
            'LV' => ['lat' => 56.9496, 'lng' => 24.1052],  // Latvia
            'LI' => ['lat' => 47.1660, 'lng' => 9.5554],   // Liechtenstein
            'LT' => ['lat' => 54.6872, 'lng' => 25.2797],  // Lithuania
            'LU' => ['lat' => 49.6116, 'lng' => 6.1319],   // Luxembourg
            'MT' => ['lat' => 35.8997, 'lng' => 14.5147],  // Malta
            'MD' => ['lat' => 47.0105, 'lng' => 28.8638],  // Moldova
            'MC' => ['lat' => 43.7384, 'lng' => 7.4246],   // Monaco
            'ME' => ['lat' => 42.4304, 'lng' => 19.2594],  // Montenegro
            'NL' => ['lat' => 51.9225, 'lng' => 4.4792],   // Netherlands - Rotterdam
            'MK' => ['lat' => 41.9973, 'lng' => 21.4280],  // North Macedonia
            'NO' => ['lat' => 59.9139, 'lng' => 10.7522],  // Norway
            'PL' => ['lat' => 54.3520, 'lng' => 18.6466],  // Poland - Gdansk
            'PT' => ['lat' => 38.7223, 'lng' => -9.1393],  // Portugal
            'RO' => ['lat' => 44.4268, 'lng' => 26.1025],  // Romania
            'RU' => ['lat' => 59.9311, 'lng' => 30.3609],  // Russia - St Petersburg
            'SM' => ['lat' => 43.9424, 'lng' => 12.4578],  // San Marino
            'RS' => ['lat' => 44.7866, 'lng' => 20.4489],  // Serbia
            'SK' => ['lat' => 48.1486, 'lng' => 17.1077],  // Slovakia
            'SI' => ['lat' => 45.5488, 'lng' => 13.7301],  // Slovenia
            'ES' => ['lat' => 39.4699, 'lng' => -0.3763],  // Spain - Valencia
            'SE' => ['lat' => 57.7089, 'lng' => 11.9746],  // Sweden - Gothenburg
            'CH' => ['lat' => 47.5596, 'lng' => 7.5886],   // Switzerland
            'TR' => ['lat' => 41.0082, 'lng' => 28.9784],  // Turkey - Istanbul
            'UA' => ['lat' => 46.4825, 'lng' => 30.7233],  // Ukraine - Odessa
            'GB' => ['lat' => 51.9614, 'lng' => 1.3511],   // UK - Felixstowe
            'VA' => ['lat' => 41.9029, 'lng' => 12.4534],  // Vatican
            
            // Asia
            'AF' => ['lat' => 34.5553, 'lng' => 69.2075],  // Afghanistan
            'BD' => ['lat' => 22.3569, 'lng' => 91.7832],  // Bangladesh - Chittagong
            'BT' => ['lat' => 27.4728, 'lng' => 89.6393],  // Bhutan
            'BN' => ['lat' => 4.9031, 'lng' => 114.9398],  // Brunei
            'KH' => ['lat' => 10.6218, 'lng' => 103.5273], // Cambodia
            'CN' => ['lat' => 31.2304, 'lng' => 121.4737], // China - Shanghai
            'IN' => ['lat' => 18.9387, 'lng' => 72.8353],  // India - Mumbai
            'ID' => ['lat' => -6.1052, 'lng' => 106.8818], // Indonesia - Jakarta
            'IR' => ['lat' => 27.1865, 'lng' => 56.2808],  // Iran
            'IQ' => ['lat' => 30.5085, 'lng' => 47.7835],  // Iraq
            'IL' => ['lat' => 31.7683, 'lng' => 35.2137],  // Israel
            'JP' => ['lat' => 35.4437, 'lng' => 139.6380], // Japan - Yokohama
            'JO' => ['lat' => 29.5321, 'lng' => 35.0063],  // Jordan
            'KZ' => ['lat' => 51.1694, 'lng' => 71.4491],  // Kazakhstan
            'KW' => ['lat' => 29.3759, 'lng' => 47.9774],  // Kuwait
            'KG' => ['lat' => 42.8746, 'lng' => 74.5698],  // Kyrgyzstan
            'LA' => ['lat' => 17.9757, 'lng' => 102.6331], // Laos
            'LB' => ['lat' => 33.8886, 'lng' => 35.4955],  // Lebanon
            'MY' => ['lat' => 3.0001, 'lng' => 101.5000],  // Malaysia
            'MV' => ['lat' => 4.1755, 'lng' => 73.5093],   // Maldives
            'MN' => ['lat' => 47.8864, 'lng' => 106.9057], // Mongolia
            'MM' => ['lat' => 16.8661, 'lng' => 96.1951],  // Myanmar
            'NP' => ['lat' => 27.7172, 'lng' => 85.3240],  // Nepal
            'KP' => ['lat' => 39.0392, 'lng' => 125.7625], // North Korea
            'OM' => ['lat' => 23.5880, 'lng' => 58.3829],  // Oman
            'PK' => ['lat' => 24.8615, 'lng' => 67.0099],  // Pakistan - Karachi
            'PS' => ['lat' => 31.5000, 'lng' => 34.4667],  // Palestine
            'PH' => ['lat' => 14.6042, 'lng' => 120.9822], // Philippines - Manila
            'QA' => ['lat' => 25.2867, 'lng' => 51.5333],  // Qatar
            'SA' => ['lat' => 21.4858, 'lng' => 39.1925],  // Saudi Arabia - Jeddah
            'SG' => ['lat' => 1.2644, 'lng' => 103.8220],  // Singapore
            'KR' => ['lat' => 35.1796, 'lng' => 129.0756], // South Korea - Busan
            'LK' => ['lat' => 6.9271, 'lng' => 79.8612],   // Sri Lanka
            'SY' => ['lat' => 33.5138, 'lng' => 36.2765],  // Syria
            'TW' => ['lat' => 25.0330, 'lng' => 121.5654], // Taiwan
            'TJ' => ['lat' => 38.5598, 'lng' => 68.7738],  // Tajikistan
            'TH' => ['lat' => 13.0827, 'lng' => 100.8831], // Thailand - Laem Chabang
            'TL' => ['lat' => -8.5569, 'lng' => 125.5603], // Timor-Leste
            'TM' => ['lat' => 37.9601, 'lng' => 58.3261],  // Turkmenistan
            'AE' => ['lat' => 25.2048, 'lng' => 55.2708],  // UAE - Dubai
            'UZ' => ['lat' => 41.2995, 'lng' => 69.2401],  // Uzbekistan
            'VN' => ['lat' => 10.7756, 'lng' => 106.7019], // Vietnam - Ho Chi Minh
            'YE' => ['lat' => 12.7855, 'lng' => 45.0187],  // Yemen
            
            // Africa
            'DZ' => ['lat' => 36.7528, 'lng' => 3.0420],   // Algeria
            'AO' => ['lat' => -8.8383, 'lng' => 13.2344],  // Angola
            'BJ' => ['lat' => 6.3654, 'lng' => 2.4183],    // Benin
            'BW' => ['lat' => -24.6282, 'lng' => 25.9231], // Botswana
            'BF' => ['lat' => 12.3714, 'lng' => -1.5197],  // Burkina Faso
            'BI' => ['lat' => -3.3731, 'lng' => 29.3644],  // Burundi
            'CM' => ['lat' => 4.0511, 'lng' => 9.7679],    // Cameroon
            'CV' => ['lat' => 14.9177, 'lng' => -23.5087], // Cape Verde
            'CF' => ['lat' => 4.3947, 'lng' => 18.5582],   // Central African Republic
            'TD' => ['lat' => 12.1348, 'lng' => 15.0557],  // Chad
            'KM' => ['lat' => -11.7022, 'lng' => 43.2551], // Comoros
            'CG' => ['lat' => -4.2634, 'lng' => 15.2429],  // Congo
            'CD' => ['lat' => -4.3297, 'lng' => 15.3152],  // DR Congo
            'CI' => ['lat' => 5.3599, 'lng' => -4.0083],   // Ivory Coast
            'DJ' => ['lat' => 11.5721, 'lng' => 43.1456],  // Djibouti
            'EG' => ['lat' => 31.2001, 'lng' => 29.9187],  // Egypt - Alexandria
            'GQ' => ['lat' => 3.7504, 'lng' => 8.7371],    // Equatorial Guinea
            'ER' => ['lat' => 15.3229, 'lng' => 38.9251],  // Eritrea
            'ET' => ['lat' => 9.0320, 'lng' => 38.7469],   // Ethiopia
            'GA' => ['lat' => 0.4162, 'lng' => 9.4673],    // Gabon
            'GM' => ['lat' => 13.4549, 'lng' => -16.5790], // Gambia
            'GH' => ['lat' => 5.6037, 'lng' => -0.1870],   // Ghana
            'GN' => ['lat' => 9.6412, 'lng' => -13.5784],  // Guinea
            'GW' => ['lat' => 11.8037, 'lng' => -15.1804], // Guinea-Bissau
            'KE' => ['lat' => -4.0435, 'lng' => 39.6682],  // Kenya - Mombasa
            'LS' => ['lat' => -29.3167, 'lng' => 27.4833], // Lesotho
            'LR' => ['lat' => 6.3156, 'lng' => -10.8074],  // Liberia
            'LY' => ['lat' => 32.8872, 'lng' => 13.1913],  // Libya
            'MG' => ['lat' => -18.8792, 'lng' => 47.5079], // Madagascar
            'MW' => ['lat' => -13.9626, 'lng' => 33.7741], // Malawi
            'ML' => ['lat' => 12.6392, 'lng' => -8.0029],  // Mali
            'MR' => ['lat' => 18.0735, 'lng' => -15.9582], // Mauritania
            'MU' => ['lat' => -20.1609, 'lng' => 57.5012], // Mauritius
            'MA' => ['lat' => 33.5731, 'lng' => -7.5898],  // Morocco - Casablanca
            'MZ' => ['lat' => -25.9655, 'lng' => 32.5832], // Mozambique
            'NA' => ['lat' => -22.5597, 'lng' => 17.0832], // Namibia
            'NE' => ['lat' => 13.5127, 'lng' => 2.1126],   // Niger
            'NG' => ['lat' => 6.4531, 'lng' => 3.3958],    // Nigeria - Lagos
            'RW' => ['lat' => -1.9403, 'lng' => 29.8739],  // Rwanda
            'ST' => ['lat' => 0.3302, 'lng' => 6.7273],    // Sao Tome
            'SN' => ['lat' => 14.6937, 'lng' => -17.4441], // Senegal
            'SC' => ['lat' => -4.6236, 'lng' => 55.4544],  // Seychelles
            'SL' => ['lat' => 8.4657, 'lng' => -13.2317],  // Sierra Leone
            'SO' => ['lat' => 2.0469, 'lng' => 45.3182],   // Somalia
            'ZA' => ['lat' => -29.8587, 'lng' => 31.0218], // South Africa - Durban
            'SS' => ['lat' => 4.8517, 'lng' => 31.5825],   // South Sudan
            'SD' => ['lat' => 15.5007, 'lng' => 32.5599],  // Sudan
            'SZ' => ['lat' => -26.3054, 'lng' => 31.1367], // Eswatini
            'TZ' => ['lat' => -6.7924, 'lng' => 39.2083],  // Tanzania
            'TG' => ['lat' => 6.1256, 'lng' => 1.2228],    // Togo
            'TN' => ['lat' => 36.8065, 'lng' => 10.1815],  // Tunisia
            'UG' => ['lat' => 0.3136, 'lng' => 32.5811],   // Uganda
            'ZM' => ['lat' => -15.4167, 'lng' => 28.2833], // Zambia
            'ZW' => ['lat' => -17.8252, 'lng' => 31.0335], // Zimbabwe
            
            // Americas
            'AR' => ['lat' => -34.6037, 'lng' => -58.3816], // Argentina - Buenos Aires
            'BS' => ['lat' => 25.0443, 'lng' => -77.3504],  // Bahamas
            'BB' => ['lat' => 13.0969, 'lng' => -59.6145],  // Barbados
            'BZ' => ['lat' => 17.4981, 'lng' => -88.1863],  // Belize
            'BO' => ['lat' => -16.4897, 'lng' => -68.1193], // Bolivia
            'BR' => ['lat' => -23.9608, 'lng' => -46.3330], // Brazil - Santos
            'CA' => ['lat' => 49.2827, 'lng' => -123.1207], // Canada - Vancouver
            'CL' => ['lat' => -33.0472, 'lng' => -71.6127], // Chile - Valparaiso
            'CO' => ['lat' => 10.9639, 'lng' => -74.7964],  // Colombia - Cartagena
            'CR' => ['lat' => 9.9281, 'lng' => -84.0907],   // Costa Rica
            'CU' => ['lat' => 23.1136, 'lng' => -82.3666],  // Cuba - Havana
            'DM' => ['lat' => 15.2976, 'lng' => -61.3900],  // Dominica
            'DO' => ['lat' => 18.4861, 'lng' => -69.9312],  // Dominican Republic
            'EC' => ['lat' => -2.1894, 'lng' => -79.8890],  // Ecuador
            'SV' => ['lat' => 13.6929, 'lng' => -89.2182],  // El Salvador
            'GF' => ['lat' => 4.9227, 'lng' => -52.3269],   // French Guiana
            'GD' => ['lat' => 12.0561, 'lng' => -61.7488],  // Grenada
            'GT' => ['lat' => 14.6349, 'lng' => -90.5069],  // Guatemala
            'GY' => ['lat' => 6.8013, 'lng' => -58.1551],   // Guyana
            'HT' => ['lat' => 18.5944, 'lng' => -72.3074],  // Haiti
            'HN' => ['lat' => 14.0650, 'lng' => -87.1715],  // Honduras
            'JM' => ['lat' => 17.9771, 'lng' => -76.7674],  // Jamaica
            'MX' => ['lat' => 19.4326, 'lng' => -99.1332],  // Mexico
            'NI' => ['lat' => 12.1150, 'lng' => -86.2362],  // Nicaragua
            'PA' => ['lat' => 8.9824, 'lng' => -79.5199],   // Panama
            'PY' => ['lat' => -25.2637, 'lng' => -57.5759], // Paraguay
            'PE' => ['lat' => -12.0464, 'lng' => -77.0428], // Peru - Lima/Callao
            'LC' => ['lat' => 14.0101, 'lng' => -60.9875],  // Saint Lucia
            'VC' => ['lat' => 13.1579, 'lng' => -61.2248],  // Saint Vincent
            'SR' => ['lat' => 5.8520, 'lng' => -55.2038],   // Suriname
            'TT' => ['lat' => 10.6918, 'lng' => -61.2225],  // Trinidad & Tobago
            'US' => ['lat' => 33.7405, 'lng' => -118.2722], // USA - Los Angeles
            'UY' => ['lat' => -34.9011, 'lng' => -56.1645], // Uruguay
            'VE' => ['lat' => 10.5000, 'lng' => -66.9167],  // Venezuela
            
            // Oceania
            'AU' => ['lat' => -37.8406, 'lng' => 144.9306],  // Australia - Melbourne
            'FJ' => ['lat' => -18.1248, 'lng' => 178.4501],  // Fiji
            'KI' => ['lat' => 1.3382, 'lng' => 173.0176],    // Kiribati
            'MH' => ['lat' => 7.1315, 'lng' => 171.1845],    // Marshall Islands
            'FM' => ['lat' => 6.9177, 'lng' => 158.1850],    // Micronesia
            'NR' => ['lat' => -0.5228, 'lng' => 166.9315],   // Nauru
            'NZ' => ['lat' => -36.8485, 'lng' => 174.7633],  // New Zealand - Auckland
            'PW' => ['lat' => 7.5150, 'lng' => 134.5825],    // Palau
            'PG' => ['lat' => -9.4438, 'lng' => 147.1803],   // Papua New Guinea
            'WS' => ['lat' => -13.8333, 'lng' => -171.7500], // Samoa
            'SB' => ['lat' => -9.4280, 'lng' => 159.9494],   // Solomon Islands
            'TO' => ['lat' => -21.1789, 'lng' => -175.1982], // Tonga
            'TV' => ['lat' => -8.5243, 'lng' => 179.1942],   // Tuvalu
            'VU' => ['lat' => -17.7333, 'lng' => 168.3167],  // Vanuatu
        ];
        
        // Return coordinates if exists, otherwise use country center approximation
        if (isset($coordinates[$country->code])) {
            return $coordinates[$country->code];
        }
        
        // Fallback: use region-based defaults
        $regionDefaults = [
            'Europe' => ['lat' => 50.0, 'lng' => 10.0],
            'Asia' => ['lat' => 25.0, 'lng' => 90.0],
            'Africa' => ['lat' => 0.0, 'lng' => 20.0],
            'Americas' => ['lat' => 0.0, 'lng' => -60.0],
            'Oceania' => ['lat' => -20.0, 'lng' => 140.0],
        ];
        
        return $regionDefaults[$country->region] ?? ['lat' => 0.0, 'lng' => 0.0];
    }
}
