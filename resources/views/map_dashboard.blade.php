<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Supply Chain Risk Dashboard</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        #map { height: 580px; width: 100%; border-radius: 12px; z-index: 1; }
        /* Mengatasi override global style link leaflet agar teks di popup tetap putih */
        .leaflet-popup-content a.btn-popup-api {
            color: #ffffff !important;
            text-decoration: none !important;
        }
        .leaflet-popup-content a.btn-popup-api:hover {
            background-color: #1d4ed8 !important;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased">

    <div class="container mx-auto p-4 lg:p-6">
        
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b border-slate-800 pb-4 gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-blue-500">
                    SUPPLY CHAIN RISK MONITORING PLATFORM
                </h1>
                <p class="text-slate-400 text-xs lg:text-sm mt-1">
                    Visualisasi Jalur Logistik Global Terintegrasi Multi-API & Lexicon-Based Sentiment Engine
                </p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-slate-900 px-4 py-2 rounded-lg border border-slate-800 text-xs md:text-sm shadow-md flex items-center gap-2">
                    <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-slate-300 font-medium">Database Terhubung & Sinkron</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 px-3 py-2 rounded-lg text-xs font-bold transition-colors cursor-pointer">
                        🚪 Keluar
                    </button>
                </form>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            <div class="lg:col-span-3 bg-slate-900 p-4 rounded-xl border border-slate-800 shadow-2xl">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-base lg:text-lg font-bold text-slate-200 flex items-center gap-2">
                        🗺️ Pemetaan Real-Time Koridor Pelabuhan Global
                    </h2>
                    <span class="text-[10px] uppercase font-bold text-blue-400 bg-blue-950/50 px-2.5 py-1 rounded border border-blue-900/30">
                        Leaflet Maps Engine Active
                    </span>
                </div>
                <div id="map" class="shadow-inner bg-slate-950 border border-slate-800"></div>
            </div>

            <div class="bg-slate-900 p-4 rounded-xl border border-slate-800 shadow-2xl flex flex-col max-h-[640px]">
                <h2 class="text-base lg:text-lg font-bold text-slate-200 mb-4 flex items-center gap-2 border-b border-slate-800 pb-2">
                    📊 Hasil Indeks Risiko Terkini
                </h2>
                
                <div class="space-y-3 overflow-y-auto flex-1 pr-1 custom-scrollbar">
                    @forelse($risks as $risk)
                        <div class="p-3.5 rounded-lg border bg-slate-950 transition-all duration-200 hover:scale-[1.02] 
                            {{ $risk->risk_level == 'High Risk' ? 'border-red-500/30 bg-red-950/5' : ($risk->risk_level == 'Medium Risk' ? 'border-amber-500/30 bg-amber-950/5' : 'border-emerald-500/30 bg-emerald-950/5') }}">
                            
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-sm text-slate-200 tracking-wide">
                                    {{ $risk->country->name }} ({{ $risk->country->code }})
                                </span>
                                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded tracking-wider
                                    {{ $risk->risk_level == 'High Risk' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : ($risk->risk_level == 'Medium Risk' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20') }}">
                                    {{ $risk->risk_level }}
                                </span>
                            </div>
                            
                            <div class="mt-3 pt-2 border-t border-slate-900 flex justify-between items-center">
                                <a href="/risk/print/{{ $risk->id }}" target="_blank" 
                                   class="bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 px-2.5 py-1.5 rounded text-[11px] font-bold transition-all no-underline flex items-center gap-1">
                                    🖨️ Dokumen PDF
                                </a>
                                <div class="text-right">
                                    <span class="text-[10px] text-slate-500 block">Weighted Score:</span>
                                    <span class="text-xl font-black text-blue-400">{{ round($risk->total_risk_score, 1) }}%</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-slate-500 text-xs border border-dashed border-slate-800 rounded-lg bg-slate-950/50">
                            <span class="text-2xl block mb-2">📥</span>
                            Belum ada rekam data kalkulasi.<br>
                            <p class="mt-2 text-slate-400">Buka link /api/risk/ID terlebih dahulu di browser untuk memicu hitungan awal.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <script>
        const map = L.map('map').setView([15.0, 30.0], 2);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            maxZoom: 18
        }).addTo(map);

        const portData = @json($ports);

        // Fungsi Global untuk Hitung Risiko menggunakan AJAX (Fetch) tanpa pindah halaman
        function hitungRisikoEfektif(countryCode) {
            // Ambil tombol yang sedang diklik untuk memberikan efek loading
            const btn = document.getElementById('btn-api-' + countryCode);
            if (btn) {
                btn.innerText = '⏳ Memproses Data API...';
                btn.style.backgroundColor = '#4b5563'; // Mengubah jadi abu-abu saat loading
                btn.style.pointerEvents = 'none';
            }

            // Menembak API backend di balik layar (AJAX Fetch)
            fetch('/api/risk/' + countryCode)
                .then(response => response.json())
                .then(data => {
                    // Jika sukses, langsung muat ulang halaman utama untuk memperbarui list dashboard kanan
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal mengambil data dari API pihak ketiga.');
                    if (btn) {
                        btn.innerText = '🚀 Hitung Ulang Risiko API';
                        btn.style.backgroundColor = '#2563eb';
                        btn.style.pointerEvents = 'auto';
                    }
                });
        }

        portData.forEach(port => {
            const marker = L.marker([port.latitude, port.longitude]).addTo(map);
            
            // Tombol sekarang memanggil fungsi JavaScript hitungRisikoEfektif, bukan membuka link langsung
            const popupContent = 
                '<div class="font-sans text-slate-900" style="min-width: 180px; padding: 2px;">' +
                    '<h3 class="font-bold text-sm text-blue-600 border-b pb-1 mb-2">🚢 ' + port.port_name + '</h3>' +
                    '<table class="w-full text-left text-[11px] text-slate-600 mb-3">' +
                        '<tr><td class="font-semibold w-16">Negara:</td><td>' + port.country_code + '</td></tr>' +
                        '<tr><td class="font-semibold">Ref ID:</td><td class="font-mono">' + (port.index_number || '-') + '</td></tr>' +
                    '</table>' +
                    '<div class="pt-2 border-t border-slate-200">' +
                        '<button onclick="hitungRisikoEfektif(\'' + port.country_code + '\')" ' +
                           'id="btn-api-' + port.country_code + '" ' +
                           'style="color: #ffffff !important; text-decoration: none !important; display: block; text-align: center; width: 100%; border: none; cursor: pointer;" ' +
                           'class="btn-popup-api text-[11px] font-bold bg-blue-600 hover:bg-blue-700 py-2 px-3 rounded text-white shadow-sm">' +
                            '🚀 Hitung Ulang Risiko API' +
                        '</button>' +
                    '</div>' +
                '</div>';
            
            marker.bindPopup(popupContent);
        });
    </script>
</body>
</html>