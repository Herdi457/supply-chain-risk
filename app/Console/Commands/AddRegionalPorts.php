<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddRegionalPorts extends Command
{
    protected $signature = 'ports:add-regional {--force}';
    protected $description = 'Add regional and local ports worldwide (small/medium ports)';

    public function handle()
    {
        $force = $this->option('force');
        
        if (!$force) {
            if (!$this->confirm('This will add 500+ regional/local ports. Continue?')) {
                return;
            }
        }

        $this->info('🌊 Adding regional & local ports worldwide...');
        
        $ports = $this->getRegionalPorts();
        
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
                if ($added % 50 == 0) {
                    $this->info("Progress: {$added} ports added...");
                }
            } else {
                $skipped++;
            }
        }
        
        $this->info("✅ Regional ports added: {$added}");
        $this->info("⏭️  Skipped (duplicates): {$skipped}");
        
        $total = DB::table('ports')->count();
        $this->info("📊 Total ports in database: {$total}");
    }

    private function getRegionalPorts()
    {
        return [
            // ============ INDONESIA - REGIONAL PORTS ============
            // Aceh
            ['name' => 'Lhokseumawe Port', 'country_code' => 'ID', 'latitude' => 5.1870, 'longitude' => 97.1427],
            ['name' => 'Banda Aceh Port (Malahayati)', 'country_code' => 'ID', 'latitude' => 5.5483, 'longitude' => 95.3238],
            ['name' => 'Sabang Port', 'country_code' => 'ID', 'latitude' => 5.8933, 'longitude' => 95.3189],
            ['name' => 'Meulaboh Port', 'country_code' => 'ID', 'latitude' => 4.1364, 'longitude' => 96.1289],
            ['name' => 'Singkil Port', 'country_code' => 'ID', 'latitude' => 2.3017, 'longitude' => 97.7892],
            
            // Sumatra North
            ['name' => 'Sibolga Port', 'country_code' => 'ID', 'latitude' => 1.7406, 'longitude' => 98.7792],
            ['name' => 'Gunung Sitoli Port', 'country_code' => 'ID', 'latitude' => 1.2858, 'longitude' => 97.6128],
            ['name' => 'Tanjung Balai Asahan Port', 'country_code' => 'ID', 'latitude' => 2.9667, 'longitude' => 99.8000],
            ['name' => 'Belawan Port', 'country_code' => 'ID', 'latitude' => 3.7831, 'longitude' => 98.6867],
            
            // Sumatra West & Riau
            ['name' => 'Padang Port (Teluk Bayur)', 'country_code' => 'ID', 'latitude' => -0.9893, 'longitude' => 100.3639],
            ['name' => 'Pekanbaru Port (Dumai)', 'country_code' => 'ID', 'latitude' => 1.6856, 'longitude' => 101.4478],
            ['name' => 'Tanjung Pinang Port', 'country_code' => 'ID', 'latitude' => 0.9186, 'longitude' => 104.4572],
            ['name' => 'Batam Port (Sekupang)', 'country_code' => 'ID', 'latitude' => 1.1244, 'longitude' => 103.9942],
            
            // Sumatra South
            ['name' => 'Jambi Port (Talang Duku)', 'country_code' => 'ID', 'latitude' => -1.5950, 'longitude' => 103.6111],
            ['name' => 'Bengkulu Port (Pulau Baai)', 'country_code' => 'ID', 'latitude' => -3.8644, 'longitude' => 102.3381],
            ['name' => 'Pangkal Pinang Port', 'country_code' => 'ID', 'latitude' => -2.1186, 'longitude' => 106.1172],
            ['name' => 'Tanjung Pandan Port', 'country_code' => 'ID', 'latitude' => -2.7419, 'longitude' => 107.6403],
            
            // Lampung
            ['name' => 'Panjang Port (Bandar Lampung)', 'country_code' => 'ID', 'latitude' => -5.4442, 'longitude' => 105.3169],
            ['name' => 'Bakauheni Port', 'country_code' => 'ID', 'latitude' => -5.8881, 'longitude' => 105.7469],
            
            // Java - West
            ['name' => 'Merak Port', 'country_code' => 'ID', 'latitude' => -5.9194, 'longitude' => 106.0017],
            ['name' => 'Cirebon Port', 'country_code' => 'ID', 'latitude' => -6.7063, 'longitude' => 108.5572],
            ['name' => 'Indramayu Port', 'country_code' => 'ID', 'latitude' => -6.3333, 'longitude' => 108.3333],
            
            // Java - Central
            ['name' => 'Semarang Port (Tanjung Emas)', 'country_code' => 'ID', 'latitude' => -6.9533, 'longitude' => 110.4253],
            ['name' => 'Tegal Port', 'country_code' => 'ID', 'latitude' => -6.8703, 'longitude' => 109.1403],
            ['name' => 'Cilacap Port', 'country_code' => 'ID', 'latitude' => -7.7281, 'longitude' => 109.0075],
            
            // Java - East
            ['name' => 'Probolinggo Port', 'country_code' => 'ID', 'latitude' => -7.7536, 'longitude' => 113.2153],
            ['name' => 'Pasuruan Port', 'country_code' => 'ID', 'latitude' => -7.6447, 'longitude' => 112.9042],
            ['name' => 'Banyuwangi Port (Ketapang)', 'country_code' => 'ID', 'latitude' => -8.4481, 'longitude' => 114.3442],
            
            // Bali & Nusa Tenggara
            ['name' => 'Benoa Port (Bali)', 'country_code' => 'ID', 'latitude' => -8.7539, 'longitude' => 115.2217],
            ['name' => 'Gilimanuk Port (Bali)', 'country_code' => 'ID', 'latitude' => -8.1619, 'longitude' => 114.4428],
            ['name' => 'Lembar Port (Lombok)', 'country_code' => 'ID', 'latitude' => -8.7214, 'longitude' => 116.0736],
            ['name' => 'Bima Port', 'country_code' => 'ID', 'latitude' => -8.4550, 'longitude' => 118.7228],
            ['name' => 'Kupang Port', 'country_code' => 'ID', 'latitude' => -10.1558, 'longitude' => 123.5769],
            ['name' => 'Labuan Bajo Port', 'country_code' => 'ID', 'latitude' => -8.4967, 'longitude' => 119.8878],
            
            // Kalimantan - West & Central
            ['name' => 'Ketapang Port', 'country_code' => 'ID', 'latitude' => -1.8467, 'longitude' => 109.9756],
            ['name' => 'Sampit Port', 'country_code' => 'ID', 'latitude' => -2.5319, 'longitude' => 112.9486],
            ['name' => 'Kumai Port', 'country_code' => 'ID', 'latitude' => -2.6892, 'longitude' => 111.7489],
            
            // Kalimantan - South & East
            ['name' => 'Kotabaru Port', 'country_code' => 'ID', 'latitude' => -3.2947, 'longitude' => 116.1678],
            ['name' => 'Tanjung Selor Port', 'country_code' => 'ID', 'latitude' => 2.8333, 'longitude' => 117.3667],
            ['name' => 'Tarakan Port', 'country_code' => 'ID', 'latitude' => 3.3272, 'longitude' => 117.6347],
            ['name' => 'Nunukan Port', 'country_code' => 'ID', 'latitude' => 4.1331, 'longitude' => 117.6667],
            ['name' => 'Samarinda Port', 'country_code' => 'ID', 'latitude' => -0.5022, 'longitude' => 117.1536],
            ['name' => 'Bontang Port', 'country_code' => 'ID', 'latitude' => 0.1333, 'longitude' => 117.5000],
            
            // Sulawesi - North & Central
            ['name' => 'Bitung Port', 'country_code' => 'ID', 'latitude' => 1.4404, 'longitude' => 125.1983],
            ['name' => 'Gorontalo Port', 'country_code' => 'ID', 'latitude' => 0.5444, 'longitude' => 123.0581],
            ['name' => 'Palu Port (Pantoloan)', 'country_code' => 'ID', 'latitude' => -0.6950, 'longitude' => 119.8708],
            ['name' => 'Luwuk Port', 'country_code' => 'ID', 'latitude' => -0.9514, 'longitude' => 122.7875],
            
            // Sulawesi - South & Southeast
            ['name' => 'Parepare Port', 'country_code' => 'ID', 'latitude' => -4.0131, 'longitude' => 119.6236],
            ['name' => 'Kendari Port', 'country_code' => 'ID', 'latitude' => -3.9450, 'longitude' => 122.5125],
            ['name' => 'Kolaka Port', 'country_code' => 'ID', 'latitude' => -4.0558, 'longitude' => 121.6114],
            ['name' => 'Bau-Bau Port', 'country_code' => 'ID', 'latitude' => -5.4747, 'longitude' => 122.6292],
            
            // Maluku
            ['name' => 'Ternate Port', 'country_code' => 'ID', 'latitude' => 0.7933, 'longitude' => 127.3675],
            ['name' => 'Tidore Port', 'country_code' => 'ID', 'latitude' => 0.6667, 'longitude' => 127.4167],
            ['name' => 'Sofifi Port', 'country_code' => 'ID', 'latitude' => 0.7367, 'longitude' => 127.5556],
            ['name' => 'Tual Port', 'country_code' => 'ID', 'latitude' => -5.6281, 'longitude' => 132.7522],
            ['name' => 'Dobo Port (Aru Islands)', 'country_code' => 'ID', 'latitude' => -5.7667, 'longitude' => 134.2167],
            
            // Papua
            ['name' => 'Sorong Port', 'country_code' => 'ID', 'latitude' => -0.8667, 'longitude' => 131.2500],
            ['name' => 'Manokwari Port', 'country_code' => 'ID', 'latitude' => -0.8667, 'longitude' => 134.0833],
            ['name' => 'Biak Port', 'country_code' => 'ID', 'latitude' => -1.1833, 'longitude' => 136.0833],
            ['name' => 'Merauke Port', 'country_code' => 'ID', 'latitude' => -8.4833, 'longitude' => 140.4000],
            ['name' => 'Timika Port', 'country_code' => 'ID', 'latitude' => -4.5333, 'longitude' => 136.8833],
            
            // ============ MALAYSIA - REGIONAL PORTS ============
            ['name' => 'Kuantan Port', 'country_code' => 'MY', 'latitude' => 3.9667, 'longitude' => 103.4333],
            ['name' => 'Kuala Terengganu Port', 'country_code' => 'MY', 'latitude' => 5.3333, 'longitude' => 103.1333],
            ['name' => 'Kemaman Port', 'country_code' => 'MY', 'latitude' => 4.2333, 'longitude' => 103.4333],
            ['name' => 'Miri Port', 'country_code' => 'MY', 'latitude' => 4.4000, 'longitude' => 113.9833],
            ['name' => 'Bintulu Port', 'country_code' => 'MY', 'latitude' => 3.1667, 'longitude' => 113.0333],
            ['name' => 'Sibu Port', 'country_code' => 'MY', 'latitude' => 2.3000, 'longitude' => 111.8167],
            ['name' => 'Tawau Port', 'country_code' => 'MY', 'latitude' => 4.2500, 'longitude' => 117.8833],
            ['name' => 'Sandakan Port', 'country_code' => 'MY', 'latitude' => 5.8333, 'longitude' => 118.1167],
            ['name' => 'Labuan Port', 'country_code' => 'MY', 'latitude' => 5.2833, 'longitude' => 115.2500],
            ['name' => 'Lumut Port', 'country_code' => 'MY', 'latitude' => 4.2333, 'longitude' => 100.6167],
            
            // ============ PHILIPPINES - REGIONAL PORTS ============
            ['name' => 'Aparri Port', 'country_code' => 'PH', 'latitude' => 18.3500, 'longitude' => 121.6333],
            ['name' => 'San Fernando (La Union) Port', 'country_code' => 'PH', 'latitude' => 16.6167, 'longitude' => 120.3167],
            ['name' => 'Vigan Port', 'country_code' => 'PH', 'latitude' => 17.5747, 'longitude' => 120.3869],
            ['name' => 'Laoag Port', 'country_code' => 'PH', 'latitude' => 18.1981, 'longitude' => 120.5933],
            ['name' => 'Legazpi Port', 'country_code' => 'PH', 'latitude' => 13.1439, 'longitude' => 123.7444],
            ['name' => 'Naga Port', 'country_code' => 'PH', 'latitude' => 13.6219, 'longitude' => 123.1811],
            ['name' => 'Sorsogon Port', 'country_code' => 'PH', 'latitude' => 12.9667, 'longitude' => 124.0000],
            ['name' => 'Catbalogan Port', 'country_code' => 'PH', 'latitude' => 11.7833, 'longitude' => 124.8833],
            ['name' => 'Tacloban Port', 'country_code' => 'PH', 'latitude' => 11.2500, 'longitude' => 125.0000],
            ['name' => 'Ormoc Port', 'country_code' => 'PH', 'latitude' => 11.0667, 'longitude' => 124.6000],
            ['name' => 'Dumaguete Port', 'country_code' => 'PH', 'latitude' => 9.3069, 'longitude' => 123.3031],
            ['name' => 'Tagbilaran Port', 'country_code' => 'PH', 'latitude' => 9.6489, 'longitude' => 123.8531],
            ['name' => 'Surigao Port', 'country_code' => 'PH', 'latitude' => 9.7833, 'longitude' => 125.5000],
            ['name' => 'Butuan Port', 'country_code' => 'PH', 'latitude' => 8.9475, 'longitude' => 125.5406],
            ['name' => 'Dipolog Port', 'country_code' => 'PH', 'latitude' => 8.5833, 'longitude' => 123.3333],
            ['name' => 'Ozamiz Port', 'country_code' => 'PH', 'latitude' => 8.1500, 'longitude' => 123.8333],
            ['name' => 'Iligan Port', 'country_code' => 'PH', 'latitude' => 8.2333, 'longitude' => 124.2333],
            ['name' => 'General Santos Port', 'country_code' => 'PH', 'latitude' => 6.1167, 'longitude' => 125.1667],
            ['name' => 'Cotabato Port', 'country_code' => 'PH', 'latitude' => 7.2167, 'longitude' => 124.2500],
            ['name' => 'Jolo Port', 'country_code' => 'PH', 'latitude' => 6.0500, 'longitude' => 121.0000],
            ['name' => 'Puerto Princesa Port', 'country_code' => 'PH', 'latitude' => 9.7333, 'longitude' => 118.7333],
            ['name' => 'Roxas Port', 'country_code' => 'PH', 'latitude' => 11.5833, 'longitude' => 122.7500],
            ['name' => 'Bacolod Port', 'country_code' => 'PH', 'latitude' => 10.6833, 'longitude' => 122.9500],
            
            // ============ THAILAND - REGIONAL PORTS ============
            ['name' => 'Sattahip Port', 'country_code' => 'TH', 'latitude' => 12.6625, 'longitude' => 100.9028],
            ['name' => 'Map Ta Phut Port', 'country_code' => 'TH', 'latitude' => 12.6500, 'longitude' => 101.1833],
            ['name' => 'Sriracha Port', 'country_code' => 'TH', 'latitude' => 13.1667, 'longitude' => 100.9167],
            ['name' => 'Chonburi Port', 'country_code' => 'TH', 'latitude' => 13.3611, 'longitude' => 100.9847],
            ['name' => 'Samut Prakan Port', 'country_code' => 'TH', 'latitude' => 13.6000, 'longitude' => 100.6000],
            ['name' => 'Samut Sakhon Port', 'country_code' => 'TH', 'latitude' => 13.5500, 'longitude' => 100.2750],
            ['name' => 'Pattani Port', 'country_code' => 'TH', 'latitude' => 6.8667, 'longitude' => 101.2500],
            ['name' => 'Narathiwat Port', 'country_code' => 'TH', 'latitude' => 6.4250, 'longitude' => 101.8250],
            ['name' => 'Krabi Port', 'country_code' => 'TH', 'latitude' => 8.0500, 'longitude' => 98.9167],
            ['name' => 'Ranong Port', 'country_code' => 'TH', 'latitude' => 9.9667, 'longitude' => 98.6333],
            ['name' => 'Trat Port', 'country_code' => 'TH', 'latitude' => 12.2500, 'longitude' => 102.5167],
            
            // ============ VIETNAM - REGIONAL PORTS ============
            ['name' => 'Hon Gai Port', 'country_code' => 'VN', 'latitude' => 20.9500, 'longitude' => 107.0833],
            ['name' => 'Cam Pha Port', 'country_code' => 'VN', 'latitude' => 21.0167, 'longitude' => 107.3167],
            ['name' => 'Nghi Son Port', 'country_code' => 'VN', 'latitude' => 19.4500, 'longitude' => 105.8167],
            ['name' => 'Vinh Port', 'country_code' => 'VN', 'latitude' => 18.6667, 'longitude' => 105.6833],
            ['name' => 'Nha Trang Port', 'country_code' => 'VN', 'latitude' => 12.2500, 'longitude' => 109.1833],
            ['name' => 'Phan Thiet Port', 'country_code' => 'VN', 'latitude' => 10.9333, 'longitude' => 108.1000],
            ['name' => 'Can Tho Port', 'country_code' => 'VN', 'latitude' => 10.0333, 'longitude' => 105.7833],
            ['name' => 'Rach Gia Port', 'country_code' => 'VN', 'latitude' => 10.0167, 'longitude' => 105.0833],
            ['name' => 'Phu Quoc Port', 'country_code' => 'VN', 'latitude' => 10.2167, 'longitude' => 103.9667],
            
            // ============ CHINA - REGIONAL PORTS ============
            ['name' => 'Yantai Port', 'country_code' => 'CN', 'latitude' => 37.5333, 'longitude' => 121.4000],
            ['name' => 'Weihai Port', 'country_code' => 'CN', 'latitude' => 37.5000, 'longitude' => 122.1167],
            ['name' => 'Lianyungang Port', 'country_code' => 'CN', 'latitude' => 34.7667, 'longitude' => 119.4500],
            ['name' => 'Ningbo Port', 'country_code' => 'CN', 'latitude' => 29.8667, 'longitude' => 121.5500],
            ['name' => 'Wenzhou Port', 'country_code' => 'CN', 'latitude' => 28.0167, 'longitude' => 120.6500],
            ['name' => 'Fuzhou Port', 'country_code' => 'CN', 'latitude' => 26.0833, 'longitude' => 119.3000],
            ['name' => 'Quanzhou Port', 'country_code' => 'CN', 'latitude' => 24.9167, 'longitude' => 118.5833],
            ['name' => 'Shantou Port', 'country_code' => 'CN', 'latitude' => 23.3667, 'longitude' => 116.6833],
            ['name' => 'Zhanjiang Port', 'country_code' => 'CN', 'latitude' => 21.2000, 'longitude' => 110.4000],
            ['name' => 'Beihai Port', 'country_code' => 'CN', 'latitude' => 21.4833, 'longitude' => 109.1000],
            ['name' => 'Haikou Port', 'country_code' => 'CN', 'latitude' => 20.0500, 'longitude' => 110.3333],
            ['name' => 'Sanya Port', 'country_code' => 'CN', 'latitude' => 18.2333, 'longitude' => 109.5000],
            
            // ============ JAPAN - REGIONAL PORTS ============
            ['name' => 'Hakodate Port', 'country_code' => 'JP', 'latitude' => 41.7833, 'longitude' => 140.7333],
            ['name' => 'Aomori Port', 'country_code' => 'JP', 'latitude' => 40.8333, 'longitude' => 140.7667],
            ['name' => 'Akita Port', 'country_code' => 'JP', 'latitude' => 39.7167, 'longitude' => 140.1167],
            ['name' => 'Niigata Port', 'country_code' => 'JP', 'latitude' => 37.9167, 'longitude' => 139.0333],
            ['name' => 'Kanazawa Port', 'country_code' => 'JP', 'latitude' => 36.6333, 'longitude' => 136.6167],
            ['name' => 'Shimizu Port', 'country_code' => 'JP', 'latitude' => 35.0167, 'longitude' => 138.5167],
            ['name' => 'Kawasaki Port', 'country_code' => 'JP', 'latitude' => 35.5167, 'longitude' => 139.7667],
            ['name' => 'Chiba Port', 'country_code' => 'JP', 'latitude' => 35.6000, 'longitude' => 140.1000],
            ['name' => 'Hitachi Port', 'country_code' => 'JP', 'latitude' => 36.6000, 'longitude' => 140.6500],
            ['name' => 'Sendai Port', 'country_code' => 'JP', 'latitude' => 38.2667, 'longitude' => 141.0167],
            ['name' => 'Hiroshima Port', 'country_code' => 'JP', 'latitude' => 34.3500, 'longitude' => 132.4667],
            ['name' => 'Okayama Port', 'country_code' => 'JP', 'latitude' => 34.6167, 'longitude' => 133.9167],
            ['name' => 'Matsuyama Port', 'country_code' => 'JP', 'latitude' => 33.8333, 'longitude' => 132.7167],
            ['name' => 'Kochi Port', 'country_code' => 'JP', 'latitude' => 33.5333, 'longitude' => 133.5333],
            ['name' => 'Kitakyushu Port', 'country_code' => 'JP', 'latitude' => 33.8833, 'longitude' => 130.8167],
            ['name' => 'Kumamoto Port', 'country_code' => 'JP', 'latitude' => 32.7833, 'longitude' => 130.6833],
            ['name' => 'Kagoshima Port', 'country_code' => 'JP', 'latitude' => 31.5833, 'longitude' => 130.5500],
            ['name' => 'Naha Port (Okinawa)', 'country_code' => 'JP', 'latitude' => 26.2167, 'longitude' => 127.6667],
            
            // ============ SOUTH KOREA - REGIONAL PORTS ============
            ['name' => 'Ulsan Port', 'country_code' => 'KR', 'latitude' => 35.5000, 'longitude' => 129.3833],
            ['name' => 'Pohang Port', 'country_code' => 'KR', 'latitude' => 36.0333, 'longitude' => 129.3667],
            ['name' => 'Sokcho Port', 'country_code' => 'KR', 'latitude' => 38.2000, 'longitude' => 128.5917],
            ['name' => 'Gunsan Port', 'country_code' => 'KR', 'latitude' => 35.9833, 'longitude' => 126.7167],
            ['name' => 'Mokpo Port', 'country_code' => 'KR', 'latitude' => 34.7833, 'longitude' => 126.3833],
            ['name' => 'Yeosu Port', 'country_code' => 'KR', 'latitude' => 34.7417, 'longitude' => 127.7500],
            ['name' => 'Masan Port', 'country_code' => 'KR', 'latitude' => 35.1833, 'longitude' => 128.5667],
            ['name' => 'Jeju Port', 'country_code' => 'KR', 'latitude' => 33.5167, 'longitude' => 126.5333],
            
            // ============ INDIA - REGIONAL PORTS ============
            ['name' => 'Kakinada Port', 'country_code' => 'IN', 'latitude' => 16.9333, 'longitude' => 82.2333],
            ['name' => 'Ennore Port', 'country_code' => 'IN', 'latitude' => 13.2167, 'longitude' => 80.3167],
            ['name' => 'Tuticorin (V.O.C) Port', 'country_code' => 'IN', 'latitude' => 8.7642, 'longitude' => 78.1348],
            ['name' => 'Kochi Port', 'country_code' => 'IN', 'latitude' => 9.9667, 'longitude' => 76.2667],
            ['name' => 'Mangalore Port', 'country_code' => 'IN', 'latitude' => 12.9167, 'longitude' => 74.8333],
            ['name' => 'Marmagao Port (Goa)', 'country_code' => 'IN', 'latitude' => 15.4000, 'longitude' => 73.8000],
            ['name' => 'Ratnagiri Port', 'country_code' => 'IN', 'latitude' => 16.9833, 'longitude' => 73.3000],
            ['name' => 'Dahej Port', 'country_code' => 'IN', 'latitude' => 21.7167, 'longitude' => 72.6000],
            ['name' => 'Pipavav Port', 'country_code' => 'IN', 'latitude' => 20.9167, 'longitude' => 71.5167],
            ['name' => 'Mundra Port', 'country_code' => 'IN', 'latitude' => 22.8333, 'longitude' => 69.7167],
            ['name' => 'Haldia Port', 'country_code' => 'IN', 'latitude' => 22.0333, 'longitude' => 88.1167],
            
            // ============ US - REGIONAL PORTS ============
            ['name' => 'Boston Port', 'country_code' => 'US', 'latitude' => 42.3601, 'longitude' => -71.0589],
            ['name' => 'Baltimore Port', 'country_code' => 'US', 'latitude' => 39.2667, 'longitude' => -76.5833],
            ['name' => 'Norfolk Port', 'country_code' => 'US', 'latitude' => 36.8468, 'longitude' => -76.2883],
            ['name' => 'Jacksonville Port', 'country_code' => 'US', 'latitude' => 30.3983, 'longitude' => -81.6111],
            ['name' => 'Tampa Port', 'country_code' => 'US', 'latitude' => 27.9447, 'longitude' => -82.4453],
            ['name' => 'Mobile Port', 'country_code' => 'US', 'latitude' => 30.6944, 'longitude' => -88.0431],
            ['name' => 'Galveston Port', 'country_code' => 'US', 'latitude' => 29.3013, 'longitude' => -94.7977],
            ['name' => 'Corpus Christi Port', 'country_code' => 'US', 'latitude' => 27.8006, 'longitude' => -97.3964],
            ['name' => 'Portland (OR) Port', 'country_code' => 'US', 'latitude' => 45.6069, 'longitude' => -122.6764],
            ['name' => 'Everett Port', 'country_code' => 'US', 'latitude' => 47.9790, 'longitude' => -122.2021],
            ['name' => 'Kodiak Port', 'country_code' => 'US', 'latitude' => 57.7900, 'longitude' => -152.4072],
            ['name' => 'Dutch Harbor Port', 'country_code' => 'US', 'latitude' => 53.8908, 'longitude' => -166.5433],
            
            // ============ CANADA - REGIONAL PORTS ============
            ['name' => 'Saint John Port', 'country_code' => 'CA', 'latitude' => 45.2733, 'longitude' => -66.0633],
            ['name' => 'Halifax Port', 'country_code' => 'CA', 'latitude' => 44.6488, 'longitude' => -63.5752],
            ['name' => 'Quebec City Port', 'country_code' => 'CA', 'latitude' => 46.8139, 'longitude' => -71.2080],
            ['name' => 'Thunder Bay Port', 'country_code' => 'CA', 'latitude' => 48.3809, 'longitude' => -89.2477],
            ['name' => 'Prince Rupert Port', 'country_code' => 'CA', 'latitude' => 54.3150, 'longitude' => -130.3208],
            ['name' => 'Nanaimo Port', 'country_code' => 'CA', 'latitude' => 49.1642, 'longitude' => -123.9364],
            ['name' => 'Victoria Port', 'country_code' => 'CA', 'latitude' => 48.4284, 'longitude' => -123.3656],
            
            // ============ AUSTRALIA - REGIONAL PORTS ============
            ['name' => 'Geelong Port', 'country_code' => 'AU', 'latitude' => -38.1500, 'longitude' => 144.3667],
            ['name' => 'Newcastle Port', 'country_code' => 'AU', 'latitude' => -32.9167, 'longitude' => 151.7833],
            ['name' => 'Wollongong Port', 'country_code' => 'AU', 'latitude' => -34.4333, 'longitude' => 150.9000],
            ['name' => 'Bunbury Port', 'country_code' => 'AU', 'latitude' => -33.3167, 'longitude' => 115.6500],
            ['name' => 'Broome Port', 'country_code' => 'AU', 'latitude' => -17.9667, 'longitude' => 122.2333],
            ['name' => 'Gladstone Port', 'country_code' => 'AU', 'latitude' => -23.8333, 'longitude' => 151.2500],
            ['name' => 'Mackay Port', 'country_code' => 'AU', 'latitude' => -21.1167, 'longitude' => 149.1833],
            ['name' => 'Bundaberg Port', 'country_code' => 'AU', 'latitude' => -24.8667, 'longitude' => 152.3500],
            
            // ============ EUROPE - REGIONAL PORTS ============
            ['name' => 'Bremerhaven Port', 'country_code' => 'DE', 'latitude' => 53.5333, 'longitude' => 8.5833],
            ['name' => 'Wilhelmshaven Port', 'country_code' => 'DE', 'latitude' => 53.5167, 'longitude' => 8.1333],
            ['name' => 'Rostock Port', 'country_code' => 'DE', 'latitude' => 54.0833, 'longitude' => 12.1333],
            ['name' => 'Kiel Port', 'country_code' => 'DE', 'latitude' => 54.3233, 'longitude' => 10.1394],
            ['name' => 'Le Havre Port', 'country_code' => 'FR', 'latitude' => 49.4833, 'longitude' => 0.1167],
            ['name' => 'Dunkirk Port', 'country_code' => 'FR', 'latitude' => 51.0333, 'longitude' => 2.3667],
            ['name' => 'Bordeaux Port', 'country_code' => 'FR', 'latitude' => 44.8378, 'longitude' => -0.5792],
            ['name' => 'Nantes Port', 'country_code' => 'FR', 'latitude' => 47.2184, 'longitude' => -1.5536],
            ['name' => 'Southampton Port', 'country_code' => 'GB', 'latitude' => 50.9097, 'longitude' => -1.4044],
            ['name' => 'Liverpool Port', 'country_code' => 'GB', 'latitude' => 53.4084, 'longitude' => -2.9916],
            ['name' => 'Bristol Port', 'country_code' => 'GB', 'latitude' => 51.4545, 'longitude' => -2.5879],
            ['name' => 'Hull Port', 'country_code' => 'GB', 'latitude' => 53.7446, 'longitude' => -0.3367],
            ['name' => 'Antwerp Port', 'country_code' => 'BE', 'latitude' => 51.2667, 'longitude' => 4.4000],
            ['name' => 'Zeebrugge Port', 'country_code' => 'BE', 'latitude' => 51.3333, 'longitude' => 3.2000],
            ['name' => 'Amsterdam Port', 'country_code' => 'NL', 'latitude' => 52.3667, 'longitude' => 4.9000],
            ['name' => 'Bilbao Port', 'country_code' => 'ES', 'latitude' => 43.3500, 'longitude' => -3.0167],
            ['name' => 'Vigo Port', 'country_code' => 'ES', 'latitude' => 42.2333, 'longitude' => -8.7167],
            ['name' => 'Lisbon Port', 'country_code' => 'PT', 'latitude' => 38.7167, 'longitude' => -9.1333],
            ['name' => 'Porto (Leixões) Port', 'country_code' => 'PT', 'latitude' => 41.1833, 'longitude' => -8.7000],
            ['name' => 'Gdansk Port', 'country_code' => 'PL', 'latitude' => 54.3667, 'longitude' => 18.6667],
            ['name' => 'Gdynia Port', 'country_code' => 'PL', 'latitude' => 54.5333, 'longitude' => 18.5500],
            ['name' => 'Stockholm Port', 'country_code' => 'SE', 'latitude' => 59.3293, 'longitude' => 18.0686],
            ['name' => 'Gothenburg Port', 'country_code' => 'SE', 'latitude' => 57.7089, 'longitude' => 11.9746],
            ['name' => 'Oslo Port', 'country_code' => 'NO', 'latitude' => 59.9139, 'longitude' => 10.7522],
            ['name' => 'Bergen Port', 'country_code' => 'NO', 'latitude' => 60.3913, 'longitude' => 5.3221],
            ['name' => 'Helsinki Port', 'country_code' => 'FI', 'latitude' => 60.1695, 'longitude' => 24.9354],
            ['name' => 'Turku Port', 'country_code' => 'FI', 'latitude' => 60.4518, 'longitude' => 22.2666],
            ['name' => 'Tallinn Port', 'country_code' => 'EE', 'latitude' => 59.4370, 'longitude' => 24.7536],
            ['name' => 'Riga Port', 'country_code' => 'LV', 'latitude' => 56.9496, 'longitude' => 24.1052],
            ['name' => 'Klaipeda Port', 'country_code' => 'LT', 'latitude' => 55.7167, 'longitude' => 21.1333],
        ];
    }
}
