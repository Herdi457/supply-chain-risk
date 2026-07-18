<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Global Supply Chain Risk Dashboard</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Chart.js untuk visualisasi data -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        #map { height: 550px; width: 100%; border-radius: 12px; z-index: 1; }
        .leaflet-popup-content a.btn-popup-api {
            color: #ffffff !important;
            text-decoration: none !important;
        }
        .leaflet-popup-content a.btn-popup-api:hover {
            background-color: #1d4ed8 !important;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #0f172a; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        
        .sidebar-transition {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased min-h-screen">

    @include('partials.navbar')

    <main id="main-content" class="max-w-7xl mx-auto p-6 lg:p-8">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b border-slate-800 pb-4 gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-blue-500">SUPPLY CHAIN RISK MONITORING PLATFORM</h1>
                <p class="text-slate-400 text-xs lg:text-sm mt-1">Visualisasi Jalur Logistik Global Terintegrasi Multi-API & Lexicon-Based Sentiment Engine</p>
            </div>
            <div class="bg-slate-900 px-4 py-2 rounded-lg border border-slate-800 text-xs font-medium shadow-md flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
                <span class="text-slate-300">Live Multi-API Terhubung</span>
                <span id="last-sync" class="text-slate-500 ml-1"></span>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="lg:col-span-3 bg-slate-900 p-4 rounded-xl border border-slate-800 shadow-2xl">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-sm lg:text-base font-bold text-slate-200 flex items-center gap-2">🗺️ Pemetaan Real-Time Koridor Pelabuhan Global</h2>
                    <span class="text-[10px] uppercase font-bold text-blue-400 bg-blue-950/50 px-2.5 py-1 rounded border border-blue-900/30">Leaflet Maps Engine Active</span>
                </div>
                <div id="map" class="shadow-inner bg-slate-950 border border-slate-800" style="height: 550px; width: 100%;"></div>
            </div>

            <div class="bg-slate-900 p-4 rounded-xl border border-slate-800 shadow-2xl flex flex-col max-h-[610px]">
                <div class="flex justify-between items-center mb-4 border-b border-slate-800 pb-2">
                    <h2 class="text-sm lg:text-base font-bold text-slate-200 flex items-center gap-2">📊 Hasil Indeks Risiko Terkini</h2>
                    <button onclick="refreshAllRisks()" id="btn-refresh-all"
                        class="text-[10px] uppercase font-bold text-blue-400 bg-blue-950/50 px-2 py-1 rounded border border-blue-900/30 hover:bg-blue-900/50 transition-all" disabled>
                        🔄 Refresh Real-Time
                    </button>
                </div>
                <div id="risk-sidebar" class="space-y-3 overflow-y-auto flex-1 pr-1 custom-scrollbar">
                    <div id="loading-risks" class="text-center py-8 text-slate-500 hidden">
                        <div class="animate-spin inline-block w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full mb-2"></div>
                        <p class="text-xs">Loading risk data...</p>
                    </div>
                    @forelse($risks as $risk)
                        <div class="p-3.5 rounded-lg border bg-slate-950 transition-all duration-200 hover:scale-[1.02] 
                            {{ $risk->risk_level == 'High Risk' ? 'border-red-500/30 bg-red-950/5' : ($risk->risk_level == 'Medium Risk' ? 'border-amber-500/30 bg-amber-950/5' : 'border-emerald-500/30 bg-emerald-950/5') }}">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-sm text-slate-200 tracking-wide">{{ $risk->country->name ?? 'Unknown' }}</span>
                                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded tracking-wider
                                    {{ $risk->risk_level == 'High Risk' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : ($risk->risk_level == 'Medium Risk' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20') }}">
                                    {{ $risk->risk_level }}
                                </span>
                            </div>
                            <div class="mt-3 pt-2 border-t border-slate-900 flex justify-between items-center">
                                <a href="/risk/print/{{ $risk->id }}" target="_blank" class="bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 px-2.5 py-1.5 rounded text-[11px] font-bold transition-all no-underline">🖨️ PDF</a>
                                <div class="text-right">
                                    <span class="text-xl font-black text-blue-400">{{ round($risk->total_risk_score, 1) }}%</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div id="empty-risks" class="text-center py-12 text-slate-500 text-xs border border-dashed border-slate-800 rounded-lg bg-slate-950/50">
                            <span class="text-2xl block mb-2">📥</span>
                            Belum ada data risiko.<br>
                            <p class="mt-2 text-slate-400">Klik tombol di dalam pin peta untuk calculate risk.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    <script>
        // ==========================================
        // LEAFLET MAP - Real-Time Port Corridors
        // ==========================================
        const map = L.map('map', {
            minZoom: 2,
            maxZoom: 10,
            zoomSnap: 0.5,
            noWrap: true,
            maxBounds: [[-85, -180], [85, 180]],
            maxBoundsViscosity: 1.0
        }).setView([15.0, 10.0], 2);

        document.getElementById('map').style.background = '#020617';

        // Tile layer - hanya dark tiles, tanpa fallback ke light tiles
        const darkTiles = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO',
            subdomains: 'abcd',
            noWrap: true,
            maxZoom: 19
        });

        darkTiles.addTo(map);

        // Perbaiki render peta setelah layout siap
        setTimeout(() => map.invalidateSize(), 100);
        window.addEventListener('load', () => map.invalidateSize());
        window.addEventListener('resize', () => map.invalidateSize());

        const portData = @json($ports);

        console.log('📊 Port data loaded:', portData.length, 'ports');
        console.log('📊 Sample port:', portData[0]);
        console.log('📊 Map container:', document.getElementById('map'));
        console.log('📊 Leaflet loaded:', typeof L !== 'undefined');
        console.log('📊 Port data:', portData);

        // Custom icon untuk marker pelabuhan
        const portIcon = L.icon({
            iconUrl: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMiIgaGVpZ2h0PSIzMiIgdmlld0JveD0iMCAwIDMyIDMyIj48Y2lyY2xlIGN4PSIxNiIgY3k9IjE2IiByPSIxMiIgZmlsbD0iIzM4OTZmZiIgc3Ryb2tlPSIjZmZmIiBzdHJva2Utd2lkdGg9IjIiLz48Y2lyY2xlIGN4PSIxNiIgY3k9IjE2IiByPSI2IiBmaWxsPSIjZmZmIi8+PC9zdmc+',
            iconSize: [28, 28],
            iconAnchor: [14, 14],
            popupAnchor: [0, -14]
        });

        // Render markers untuk setiap pelabuhan
        console.log('🗺️ Starting to render markers...');
        if (portData && portData.length > 0) {
            let markersAdded = 0;
            portData.forEach((port, index) => {
                if (port.latitude && port.longitude) {
                    try {
                        console.log(`Adding marker ${index + 1}/${portData.length}: ${port.port_name} at [${port.latitude}, ${port.longitude}]`);
                        const marker = L.marker([port.latitude, port.longitude], { icon: portIcon }).addTo(map);
                        
                        const popupContent = `
                            <div style="min-width: 220px; font-family: system-ui, -apple-system, sans-serif;">
                                <div style="border-bottom: 2px solid #3b82f6; padding-bottom: 8px; margin-bottom: 10px;">
                                    <h3 style="margin: 0; color: #1e293b; font-size: 16px; font-weight: 700;">
                                        🚢 ${port.port_name}
                                    </h3>
                                    <p style="margin: 4px 0 0 0; color: #64748b; font-size: 12px; font-weight: 600;">
                                        📍 ${port.country_name}
                                    </p>
                                </div>
                                
                                <div style="background: #f1f5f9; padding: 10px; border-radius: 6px; margin-bottom: 10px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                        <span style="color: #475569; font-size: 11px; font-weight: 600;">Latitude:</span>
                                        <span style="color: #1e293b; font-size: 11px; font-weight: 700;">${parseFloat(port.latitude).toFixed(4)}°</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <span style="color: #475569; font-size: 11px; font-weight: 600;">Longitude:</span>
                                        <span style="color: #1e293b; font-size: 11px; font-weight: 700;">${parseFloat(port.longitude).toFixed(4)}°</span>
                                    </div>
                                </div>
                                
                                <button 
                                    id="btn-api-${port.country_code}"
                                    onclick="console.log('Button clicked for ${port.country_code}'); hitungRisikoEfektif('${port.country_code}');"
                                    class="btn-popup-api"
                                    style="
                                        width: 100%;
                                        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
                                        color: white;
                                        border: none;
                                        padding: 10px 16px;
                                        border-radius: 6px;
                                        cursor: pointer;
                                        font-size: 12px;
                                        font-weight: 700;
                                        text-align: center;
                                        transition: all 0.2s;
                                        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);
                                        text-transform: uppercase;
                                        letter-spacing: 0.5px;
                                    "
                                    onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 8px rgba(37, 99, 235, 0.4)';"
                                    onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 4px rgba(37, 99, 235, 0.3)';"
                                >
                                    🚀 Hitung Risiko ${port.country_code}
                                </button>
                                
                                <p style="margin: 8px 0 0 0; color: #94a3b8; font-size: 10px; text-align: center; font-style: italic;">
                                    Klik tombol untuk analisis risiko negara
                                </p>
                            </div>
                        `;
                        
                        marker.bindPopup(popupContent, {
                            maxWidth: 280,
                            className: 'custom-popup'
                        });
                        
                        // Add click event listener for the button when popup opens
                        marker.on('popupopen', function() {
                            const btn = document.getElementById('btn-api-' + port.country_code);
                            if (btn) {
                                btn.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    console.log('Button clicked for', port.country_code);
                                    hitungRisikoEfektif(port.country_code);
                                });
                            }
                        });
                        
                        markersAdded++;
                    } catch (e) {
                        console.error('Error adding marker for port:', port.port_name, e);
                    }
                } else {
                    console.warn(`Skipping ${port.port_name}: missing coordinates`);
                }
            });

            console.log(`✅ ${markersAdded} pelabuhan berhasil ditampilkan di peta dari ${portData.length} total`);
        } else {
            console.warn('⚠️ Tidak ada data pelabuhan. Silakan seed database terlebih dahulu.');
            
            // Tampilkan notifikasi di peta
            const noDataMarker = L.marker([0, 0]).addTo(map);
            noDataMarker.bindPopup(`
                <div style="padding: 15px; text-align: center; font-family: system-ui;">
                    <h4 style="color: #ef4444; margin: 0 0 10px 0;">⚠️ Tidak Ada Data Pelabuhan</h4>
                    <p style="color: #64748b; font-size: 12px; margin: 0;">
                        Silakan jalankan:<br>
                        <code style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 11px;">
                            php artisan db:seed
                        </code>
                    </p>
                </div>
            `).openPopup();
        }

        function hitungRisikoEfektif(countryCode) {
            console.log('🎯 Starting risk calculation for:', countryCode);
            const btn = document.getElementById('btn-api-' + countryCode);
            if (btn) {
                btn.innerText = '⏳ Memproses Data API...';
                btn.style.background = 'linear-gradient(135deg, #6b7280 0%, #4b5563 100%)';
                btn.style.pointerEvents = 'none';
                btn.style.cursor = 'wait';
            }

            // Menembak endpoint API Laravel
            console.log('🎯 Fetching /api/risk/' + countryCode);
            fetch('/api/risk/' + countryCode)
                .then(response => {
                    console.log('🎯 Response received:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('🚀 Risk calculation response:', data);
                    console.log('🚀 Success status:', data.success);
                    if (data.success) {
                        // Tampilkan notifikasi sukses
                        const notification = document.createElement('div');
                        notification.innerHTML = `
                            <div style="
                                position: fixed;
                                top: 20px;
                                right: 20px;
                                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                color: white;
                                padding: 16px 24px;
                                border-radius: 8px;
                                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
                                z-index: 10000;
                                font-family: system-ui;
                                font-weight: 600;
                                animation: slideIn 0.3s ease-out;
                            ">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <span style="font-size: 24px;">🚀</span>
                                    <div>
                                        <div style="font-size: 14px; margin-bottom: 4px;">Berhasil!</div>
                                        <div style="font-size: 12px; opacity: 0.9;">${data.message}</div>
                                    </div>
                                </div>
                            </div>
                        `;
                        document.body.appendChild(notification);
                        setTimeout(() => notification.remove(), 3000);
                        console.log('✅ Risk calculation SUCCESS for:', countryCode);
                        console.log('🔄 Refreshing sidebar after risk calculation for country:', countryCode);
                        console.log('🔄 Waiting 2 seconds before refresh to ensure DB save...');
                        setTimeout(() => {
                            console.log('🔄 Executing delayed refresh...');
                            refreshRiskSidebar();
                        }, 2000); // Delay 2 detik untuk memastikan data tersimpan
                        
                        // Scroll ke sidebar untuk melihat hasil risiko
                        setTimeout(() => {
                            const sidebar = document.getElementById('risk-sidebar');
                            if (sidebar) {
                                sidebar.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                console.log('Scrolled to sidebar');
                            }
                        }, 500);
                    } else {
                        console.log('❌ Risk calculation FAILED for:', countryCode);
                        console.log('❌ Error message:', data.message);
                        alert('❌ Gagal memproses analisis: ' + data.message);
                        if (btn) {
                            btn.innerText = '🚀 Hitung Risiko ' + countryCode;
                            btn.style.background = 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)';
                            btn.style.pointerEvents = 'auto';
                            btn.style.cursor = 'pointer';
                        }
                    }
                })
                .catch(error => {
                    console.error('❌ Network error in risk calculation:', error);
                    alert('❌ Terjadi kesalahan jaringan atau sistem.\n\n' + error.message);
                    if (btn) {
                        btn.innerText = '🚀 Hitung Risiko ' + countryCode;
                        btn.style.background = 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)';
                        btn.style.pointerEvents = 'auto';
                        btn.style.cursor = 'pointer';
                    }
                });
        }

        // Animation keyframes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            .leaflet-popup-content-wrapper {
                border-radius: 12px !important;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15) !important;
            }
            
            .leaflet-popup-tip {
                display: none !important;
            }
        `;
        document.head.appendChild(style);

        // ==========================================
        // REAL-TIME SIDEBAR REFRESH (Auto setiap 60 detik)
        // ==========================================
        function getRiskLevelClass(level) {
            if (level === 'High Risk') return {
                card: 'border-red-500/30 bg-red-950/5',
                badge: 'bg-red-500/10 text-red-400 border border-red-500/20'
            };
            if (level === 'Medium Risk') return {
                card: 'border-amber-500/30 bg-amber-950/5',
                badge: 'bg-amber-500/10 text-amber-400 border border-amber-500/20'
            };
            return {
                card: 'border-emerald-500/30 bg-emerald-950/5',
                badge: 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'
            };
        }

        async function refreshRiskSidebar() {
            try {
                console.log('🔄 Starting sidebar refresh...');
                const response = await fetch('/api/risk?limit=20&sort=desc'); // Increased limit to 20
                const data = await response.json();
                console.log('🔄 API response:', data);
                console.log('🔄 Total risk records in response:', data.data?.length);
                
                if (!data.success) {
                    console.log('🔄 API returned unsuccessful');
                    return;
                }

                const sidebar = document.getElementById('risk-sidebar');
                console.log('🔄 Sidebar element:', sidebar);
                console.log('🔄 Sidebar ID matches:', sidebar?.id === 'risk-sidebar');
                console.log('🔄 Sidebar current HTML length:', sidebar?.innerHTML?.length);
                
                if (!data.data.length) {
                    console.log('🔄 No risk data found');
                    sidebar.innerHTML = `<div class="text-center py-12 text-slate-500 text-xs border border-dashed border-slate-800 rounded-lg bg-slate-950/50">
                        <span class="text-2xl block mb-2">📥</span>Belum ada data risiko.</div>`;
                    return;
                }

                console.log('🔄 Found', data.data.length, 'risk records');
                console.log('🔄 All country codes in response:', data.data.map(r => r.country?.code || r.country_id).join(', '));
                console.log('🔄 Starting to render HTML...');
                
                const htmlContent = data.data.map(risk => {
                    const cls = getRiskLevelClass(risk.risk_level);
                    const countryName = risk.country?.name || risk.country_id;
                    const countryCode = risk.country?.code || risk.country_id;
                    console.log('🔄 Rendering risk:', countryCode, countryName, risk.total_risk_score);
                    return `<div class="p-3.5 rounded-lg border bg-slate-950 transition-all duration-200 hover:scale-[1.02] ${cls.card}">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-sm text-slate-200 tracking-wide">${countryName}</span>
                            <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded tracking-wider ${cls.badge}">${risk.risk_level}</span>
                        </div>
                        <div class="mt-3 pt-2 border-t border-slate-900 flex justify-between items-center">
                            <a href="/risk/print/${risk.id}" target="_blank" class="bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 px-2.5 py-1.5 rounded text-[11px] font-bold transition-all no-underline">🖨️ PDF</a>
                            <span class="text-xl font-black text-blue-400">${Math.round(risk.total_risk_score * 10) / 10}%</span>
                        </div>
                    </div>`;
                }).join('');
                
                console.log('🔄 HTML content length:', htmlContent.length);
                console.log('🔄 Setting innerHTML...');
                sidebar.innerHTML = htmlContent;
                console.log('🔄 innerHTML set successfully');
                console.log('🔄 Sidebar HTML after set:', sidebar.innerHTML.substring(0, 200) + '...');
                console.log('🔄 Sidebar HTML length after set:', sidebar.innerHTML.length);
                
                document.getElementById('last-sync').textContent = '• Sync: ' + new Date().toLocaleTimeString('id-ID');
                
                // Flash effect untuk menandai sidebar sudah di-update
                console.log('🔄 Applying flash effect...');
                sidebar.style.backgroundColor = '#ff0000'; // Red flash for visibility
                sidebar.style.border = '3px solid #ff0000';
                setTimeout(() => {
                    sidebar.style.backgroundColor = '';
                    sidebar.style.border = '';
                    console.log('🔄 Flash effect removed');
                }, 1000);
                
                console.log('✅ Sidebar refresh complete');
            } catch (err) {
                console.error('❌ Gagal refresh sidebar:', err);
            }
        }

        async function refreshAllRisks() {
            const btn = document.getElementById('btn-refresh-all');
            btn.textContent = '⏳ Memproses...';
            btn.disabled = true;

            try {
                console.log('Starting risk refresh...');
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                const response = await fetch('/api/risk/refresh-all', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken || '',
                        'Accept': 'application/json'
                    }
                });
                console.log('Refresh response:', response);
                const data = await response.json();
                console.log('Refresh data:', data);
                if (data.success) {
                    await refreshRiskSidebar();
                    alert('✅ ' + data.message);
                } else {
                    alert('❌ ' + data.message);
                }
            } catch (err) {
                console.error('Refresh error:', err);
                alert('❌ Gagal refresh: ' + err.message);
            } finally {
                btn.textContent = '🔄 Refresh Real-Time';
                btn.disabled = false;
            }
        }

        // Auto-refresh sidebar setiap 30 detik untuk real-time yang lebih responsif
        setInterval(refreshRiskSidebar, 30000);
        refreshRiskSidebar();

        // Auto-refresh data pelabuhan setiap 60 detik
        setInterval(async () => {
            try {
                const response = await fetch('/api/ports');
                const data = await response.json();
                if (data.success) {
                    console.log('🔄 Port data refreshed:', data.total, 'ports');
                }
            } catch (err) {
                console.error('Gagal refresh port data:', err);
            }
        }, 60000);

        // Enable refresh button after script loads
        document.getElementById('btn-refresh-all').disabled = false;
    </script>