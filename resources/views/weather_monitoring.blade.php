<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Global Weather Monitoring - GSCMS Platform</title>
	
	<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
	
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
	<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
	
	<!-- Leaflet MarkerCluster for Performance -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
	<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
	<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
	
	<style>
		#weather-map { height: 500px; width: 100%; border-radius: 12px; z-index: 1; }
		.custom-scrollbar::-webkit-scrollbar { width: 6px; }
		.custom-scrollbar::-webkit-scrollbar-track { background: #0f172a; }
		.custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
		
		/* Custom cluster styling */
		.marker-cluster-small {
			background-color: rgba(59, 130, 246, 0.6);
		}
		.marker-cluster-small div {
			background-color: rgba(37, 99, 235, 0.8);
			color: white;
			font-weight: bold;
		}
		.marker-cluster-medium {
			background-color: rgba(245, 158, 11, 0.6);
		}
		.marker-cluster-medium div {
			background-color: rgba(245, 158, 11, 0.8);
			color: white;
			font-weight: bold;
		}
		.marker-cluster-large {
			background-color: rgba(239, 68, 68, 0.6);
		}
		.marker-cluster-large div {
			background-color: rgba(220, 38, 38, 0.8);
			color: white;
			font-weight: bold;
		}
	</style>
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased min-h-screen">

	@include('partials.navbar')

	<main id="main-content" class="max-w-7xl mx-auto p-6 lg:p-8">
		<header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b border-slate-800 pb-4 gap-4">
			<div>
				<h1 class="text-2xl lg:text-3xl font-black tracking-tight text-blue-500 flex items-center gap-2">
					🌤️ GLOBAL WEATHER MONITORING
				</h1>
				<p class="text-slate-400 text-xs lg:text-sm mt-1">
					Monitoring kondisi cuaca ekstrem (Hujan, Badai, Angin Kencang) di titik pelabuhan dan koridor logistik dunia
				</p>
			</div>
			<div class="bg-slate-900 px-4 py-2 rounded-lg border border-slate-800 text-xs font-medium shadow-md flex items-center gap-2">
				<span class="w-2.5 h-2.5 bg-sky-500 rounded-full animate-pulse"></span>
				<span class="text-slate-300">Open-Meteo Live API</span>
			</div>
		</header>

		<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
			<!-- Peta Utama -->
			<div class="lg:col-span-3 bg-slate-900 p-4 rounded-xl border border-slate-800 shadow-2xl">
				<div class="flex justify-between items-center mb-3">
					<h2 class="text-sm lg:text-base font-bold text-slate-200 flex items-center gap-2">🗺️ Peta Cuaca Logistik Global</h2>
					<span class="text-[10px] uppercase font-bold text-sky-400 bg-sky-950/50 px-2.5 py-1 rounded border border-sky-900/30">{{ count($ports) }} Ports</span>
				</div>
				<div id="weather-map" class="shadow-inner bg-slate-950 border border-slate-800"></div>
			</div>

			<!-- Panel Samping: Pencarian dan Status Cuaca Detail -->
			<div class="bg-slate-900 p-4 rounded-xl border border-slate-800 shadow-2xl flex flex-col max-h-[560px]">
				<h2 class="text-sm lg:text-base font-bold text-slate-200 mb-4 flex items-center gap-2 border-b border-slate-800 pb-2">🔍 Cari Lokasi</h2>
				
				<!-- Input Pencarian -->
				<div class="mb-4">
					<input type="text" id="search-input" placeholder="Cari pelabuhan atau negara..." 
						class="w-full bg-slate-950 text-slate-200 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 placeholder-slate-500">
				</div>

				<!-- List Pelabuhan -->
				<div class="overflow-y-auto flex-1 pr-1 custom-scrollbar space-y-2" id="port-list">
					@foreach($ports as $port)
						<button onclick="selectPort({{ $port->latitude }}, {{ $port->longitude }}, '{{ $port->port_name }}', '{{ $port->country_code }}')" 
							class="w-full text-left p-2.5 rounded-lg border border-slate-800/60 bg-slate-950 hover:bg-slate-800/40 hover:border-slate-700 transition-all text-xs font-medium flex items-center justify-between port-item-btn"
							data-name="{{ strtolower($port->port_name) }} {{ strtolower($port->country_code) }}">
							<div>
								<p class="font-bold text-slate-200">🚢 {{ $port->port_name }}</p>
								<p class="text-slate-400 mt-0.5">📍 {{ $port->country_code }}</p>
							</div>
							<span class="text-[10px] text-slate-500 font-mono">{{ round($port->latitude, 2) }}, {{ round($port->longitude, 2) }}</span>
						</button>
					@endforeach
				</div>

				<!-- Panel Detail Cuaca Realtime (Dinamis via AJAX) -->
				<div id="weather-detail-card" class="hidden mt-4 pt-4 border-t border-slate-800">
					<h3 class="text-xs uppercase font-extrabold text-blue-500 tracking-wider mb-2">Cuaca Terkini</h3>
					<div class="bg-slate-950 p-3 rounded-lg border border-slate-800 space-y-2.5">
						<div class="flex justify-between items-center">
							<span id="detail-port-name" class="font-bold text-sm text-slate-200 font-sans">Shanghai</span>
							<span id="weather-badge" class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded">Aman</span>
						</div>
						<div class="grid grid-cols-3 gap-2 text-center pt-2">
							<div class="bg-slate-900/50 p-1.5 rounded border border-slate-800">
								<span class="text-lg">🌡️</span>
								<p class="text-[10px] text-slate-400 font-bold">Suhu</p>
								<p id="detail-temp" class="text-xs font-black text-white">--°C</p>
							</div>
							<div class="bg-slate-900/50 p-1.5 rounded border border-slate-800">
								<span class="text-lg">🌧️</span>
								<p class="text-[10px] text-slate-400 font-bold">Hujan</p>
								<p id="detail-rain" class="text-xs font-black text-white">-- mm</p>
							</div>
							<div class="bg-slate-900/50 p-1.5 rounded border border-slate-800">
								<span class="text-lg">💨</span>
								<p class="text-[10px] text-slate-400 font-bold">Angin</p>
								<p id="detail-wind" class="text-xs font-black text-white">-- km/h</p>
							</div>
						</div>
						<div class="text-[11px] text-slate-400 mt-2 flex flex-col gap-1" id="weather-indicators">
							<!-- weather alerts (Hujan, Badai, Angin Kencang) -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>

	<script>
		// Setup Map dengan fallback tile layer
		const weatherMap = L.map('weather-map', {
			minZoom: 2,
			maxZoom: 10,
			zoomSnap: 0.5,
			noWrap: true,
			maxBounds: [[-85, -180], [85, 180]],
			maxBoundsViscosity: 1.0
		}).setView([15.0, 10.0], 2);

		document.getElementById('weather-map').style.background = '#020617';

		const darkTiles = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
			attribution: '&copy; OpenStreetMap &copy; CARTO',
			subdomains: 'abcd',
			noWrap: true
		});
		const osmTiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; OpenStreetMap',
			subdomains: 'abc',
			noWrap: true
		});
		darkTiles.addTo(weatherMap);
		darkTiles.on('tileerror', () => {
			if (!weatherMap.hasLayer(osmTiles)) {
				weatherMap.removeLayer(darkTiles);
				osmTiles.addTo(weatherMap);
			}
		});
		setTimeout(() => weatherMap.invalidateSize(), 100);
		window.addEventListener('load', () => weatherMap.invalidateSize());

		// Custom icon untuk cuaca normal, hujan/angin kencang, dan badai
		const normalIcon = L.icon({
			iconUrl: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMiIgaGVpZ2h0PSIzMiIgdmlld0JveD0iMCAwIDMyIDMyIj48Y2lyY2xlIGN4PSIxNiIgY3k9IjE2IiByPSIxMiIgZmlsbD0iIzEwYjk4MSIgc3Ryb2tlPSIjZmZmIiBzdHJva2Utd2lkdGg9IjIiLz48L3N2Zz4=',
			iconSize: [20, 20],
			iconAnchor: [10, 10],
			popupAnchor: [0, -10]
		});

		const warningIcon = L.icon({
			iconUrl: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMiIgaGVpZ2h0PSIzMiIgdmlld0JveD0iMCAwIDMyIDMyIj48Y2lyY2xlIGN4PSIxNiIgY3k9IjE2IiByPSIxMiIgZmlsbD0iI2Y1OWUwNSIgc3Ryb2tlPSIjZmZmIiBzdHJva2Utd2lkdGg9IjIiLz48L3N2Zz4=',
			iconSize: [20, 20],
			iconAnchor: [10, 10],
			popupAnchor: [0, -10]
		});

		const dangerIcon = L.icon({
			iconUrl: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMiIgaGVpZ2h0PSIzMiIgdmlld0JveD0iMCAwIDMyIDMyIj48Y2lyY2xlIGN4PSIxNiIgY3k9IjE2IiByPSIxMiIgZmlsbD0iI2VmNDQ0NCIgc3Ryb2tlPSIjZmZmIiBzdHJva2Utd2lkdGg9IjIiLz48L3N2Zz4=',
			iconSize: [20, 20],
			iconAnchor: [10, 10],
			popupAnchor: [0, -10]
		});

		const portData = @json($ports);
		const markers = {};
		
		// Create MarkerClusterGroup for better performance
		const markerCluster = L.markerClusterGroup({
			maxClusterRadius: 80, // Distance untuk cluster
			spiderfyOnMaxZoom: true, // Pecah cluster saat zoom max
			showCoverageOnHover: false,
			zoomToBoundsOnClick: true,
			chunkedLoading: true, // Load markers in chunks
			chunkInterval: 200, // 200ms per chunk
			chunkDelay: 50, // 50ms delay between chunks
			iconCreateFunction: function(cluster) {
				const count = cluster.getChildCount();
				let c = ' marker-cluster-';
				if (count < 10) {
					c += 'small';
				} else if (count < 50) {
					c += 'medium';
				} else {
					c += 'large';
				}
				return new L.DivIcon({ 
					html: '<div><span>' + count + '</span></div>', 
					className: 'marker-cluster' + c, 
					iconSize: new L.Point(40, 40) 
				});
			}
		});

		// Render markers dengan clustering (LAZY LOADING)
		let markersAdded = 0;
		portData.forEach((port, index) => {
			if (port.latitude && port.longitude) {
				// Add markers in batches untuk avoid freeze
				setTimeout(() => {
					const marker = L.marker([port.latitude, port.longitude], { icon: normalIcon });
					markers[`${port.latitude}_${port.longitude}`] = marker;
					
					marker.bindPopup(`
						<div style="font-family: system-ui, sans-serif; min-width: 180px;">
							<h4 style="margin:0; font-size:14px; font-weight:700; color:#1e293b;">🚢 ${port.port_name}</h4>
							<p style="margin:4px 0 8px 0; color:#64748b; font-size:11px;">📍 ${port.country_code}</p>
							<button onclick="fetchPortWeather(${port.latitude}, ${port.longitude}, '${port.port_name.replace(/'/g, "\\'")}', '${port.country_code}')" 
								style="width:100%; background:#2563eb; color:#fff; border:none; padding:6px 12px; border-radius:4px; font-size:11px; font-weight:600; cursor:pointer;">
								⛅ Cek Cuaca Real-Time
							</button>
						</div>
					`);
					
					// Add to cluster instead of map directly
					markerCluster.addLayer(marker);
					markersAdded++;
					
					// Add cluster to map after first batch
					if (markersAdded === 100 && !weatherMap.hasLayer(markerCluster)) {
						weatherMap.addLayer(markerCluster);
						console.log('✅ First 100 markers clustered and added to map');
					}
				}, Math.floor(index / 100) * 50); // Batch 100 markers every 50ms
			}
		});
		
		// Ensure cluster is added after all markers loaded
		setTimeout(() => {
			if (!weatherMap.hasLayer(markerCluster)) {
				weatherMap.addLayer(markerCluster);
			}
			console.log(`✅ All ${markersAdded} markers clustered successfully`);
		}, portData.length * 0.5 + 500);

		function inArray(needle, haystack) {
			return haystack.indexOf(needle) > -1;
		}

		// Fungsi dipicu ketika user memilih port di daftar/klik tombol cek cuaca
		function selectPort(lat, lng, portName, countryCode) {
			weatherMap.setView([lat, lng], 6);
			const marker = markers[`${lat}_${lng}`];
			if (marker) {
				marker.openPopup();
			}
			fetchPortWeather(lat, lng, portName, countryCode);
		}

		function fetchPortWeather(lat, lng, portName, countryCode) {
			const detailCard = document.getElementById('weather-detail-card');
			detailCard.classList.remove('hidden');

			document.getElementById('detail-port-name').innerText = `${portName} (${countryCode})`;
			document.getElementById('detail-temp').innerText = '--°C';
			document.getElementById('detail-rain').innerText = '-- mm';
			document.getElementById('detail-wind').innerText = '-- km/h';

			const indicators = document.getElementById('weather-indicators');
			indicators.innerHTML = `<span class="text-slate-500 animate-pulse text-center block">Memuat data cuaca...</span>`;

			// Use Laravel API endpoint with caching instead of direct Open-Meteo API
			const url = `/api/weather?lat=${lat}&lng=${lng}`;
			
			fetch(url)
				.then(res => res.json())
				.then(response => {
					if (response.success && response.data && response.data.current) {
						const current = response.data.current;
						const temp = current.temperature_2m;
						const rain = current.rain;
						const wind = current.wind_speed_10m;
						const code = current.weather_code;

						document.getElementById('detail-temp').innerText = `${temp}°C`;
						document.getElementById('detail-rain').innerText = `${rain} mm`;
						document.getElementById('detail-wind').innerText = `${wind} km/h`;
						
						// Update marker icon based on weather
						const marker = markers[`${lat}_${lng}`];
						if (marker) {
							if (inArray(code, [95, 96, 99]) || wind > 50) {
								marker.setIcon(dangerIcon);
							} else if (rain > 5 || wind > 30) {
								marker.setIcon(warningIcon);
							} else {
								marker.setIcon(normalIcon);
							}
						}
						let statusText = "Aman / Normal";
						let badgeColor = "bg-emerald-500/10 text-emerald-400 border border-emerald-500/20";
						let indicatorHtml = "";

						// Kondisi Hujan
						if (rain > 0) {
							if (rain > 5) {
								indicatorHtml += `<span class="text-amber-400">🌧️ Hujan Lebat (\${rain} mm)</span>`;
							} else {
								indicatorHtml += `<span class="text-slate-300">💧 Hujan Ringan (\${rain} mm)</span>`;
							}
						} else {
							indicatorHtml += `<span class="text-slate-400">☀️ Cerah / Tidak Hujan</span>`;
						}

						// Kondisi Angin Kencang
						if (wind > 30) {
							indicatorHtml += `<span class="text-amber-400">💨 Angin Kencang (\${wind} km/h)</span>`;
							statusText = "Siaga / Angin Kencang";
							badgeColor = "bg-amber-500/10 text-amber-400 border border-amber-500/20";
						}

						// Kondisi Badai (Thunderstorm code)
						if (inArray(code, [95, 96, 99])) {
							indicatorHtml += `<span class="text-red-400 font-extrabold">⛈️ Risiko Badai Ekstrem (Thunderstorm)</span>`;
							statusText = "Bahaya / Badai";
							badgeColor = "bg-red-500/10 text-red-400 border border-red-500/20";
						} else if (wind > 50) {
							indicatorHtml += `<span class="text-red-400 font-extrabold">🌪️ Angin Badai Ekstrem</span>`;
							statusText = "Bahaya / Badai";
							badgeColor = "bg-red-500/10 text-red-400 border border-red-500/20";
						}

						const badge = document.getElementById('weather-badge');
						badge.className = `text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded \${badgeColor}`;
						badge.innerText = statusText;

						indicators.innerHTML = indicatorHtml;
					} else {
						// Handle API error or rate limit
						indicators.innerHTML = `<span class="text-red-400 text-center block">❌ ${response.message || 'Gagal memuat data cuaca'}</span>`;
						if (response.error === 'API_UNAVAILABLE') {
							indicators.innerHTML += `<span class="text-slate-500 text-[10px] text-center block mt-1">API cuaca sedang tidak tersedia. Coba lagi nanti.</span>`;
						}
					}
				})
				.catch(err => {
					console.error("Error loading weather details", err);
					indicators.innerHTML = `<span class="text-red-400">❌ Gagal memuat data cuaca</span>`;
				});
		}

		// Fitur Pencarian Pelabuhan
		const searchInput = document.getElementById('search-input');
		searchInput.addEventListener('input', function() {
			const query = this.value.toLowerCase().trim();
			const items = document.querySelectorAll('.port-item-btn');

			items.forEach(item => {
				const nameAttr = item.getAttribute('data-name');
				if (nameAttr.includes(query)) {
					item.style.display = 'flex';
				} else {
					item.style.display = 'none';
				}
			});
		});
	</script>
</body>
</html>
