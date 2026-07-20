<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vessel Route Calculator - Supply Chain Risk</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #0f172a; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        #routeMap { height: 500px; border-radius: 12px; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased">

    @include('partials.navbar')

    <main class="max-w-7xl mx-auto p-6 lg:p-8">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b border-slate-800 pb-4 gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-blue-500">🚢 VESSEL ROUTE CALCULATOR</h1>
                <p class="text-slate-400 text-xs lg:text-sm mt-1">Calculate Shipping Time, Distance & Cost Between Ports</p>
            </div>
        </header>

        <!-- Route Input Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Origin Port -->
            <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                <h2 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                    📍 Origin Port
                </h2>
                <select id="originPort" class="w-full bg-slate-950 border border-slate-700 text-slate-200 px-4 py-3 rounded-lg focus:outline-none focus:border-blue-500 mb-3">
                    <option value="">-- Select Origin Port --</option>
                </select>
                <div id="originInfo" class="text-xs text-slate-400 hidden">
                    <p id="originCoords"></p>
                </div>
            </div>

            <!-- Destination Port -->
            <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                <h2 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                    🎯 Destination Port
                </h2>
                <select id="destinationPort" class="w-full bg-slate-950 border border-slate-700 text-slate-200 px-4 py-3 rounded-lg focus:outline-none focus:border-blue-500 mb-3">
                    <option value="">-- Select Destination Port --</option>
                </select>
                <div id="destinationInfo" class="text-xs text-slate-400 hidden">
                    <p id="destinationCoords"></p>
                </div>
            </div>

            <!-- Vessel Type -->
            <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                <h2 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                    ⚓ Vessel Type
                </h2>
                <select id="vesselType" class="w-full bg-slate-950 border border-slate-700 text-slate-200 px-4 py-3 rounded-lg focus:outline-none focus:border-blue-500 mb-3">
                    <option value="40">Passenger/Ferry (40 km/h)</option>
                    <option value="30" selected>Container Ship (30 km/h)</option>
                    <option value="25">Cargo Ship (25 km/h)</option>
                    <option value="20">Bulk Carrier (20 km/h)</option>
                    <option value="15">Tanker (15 km/h)</option>
                </select>
                <button onclick="calculateRoute()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition-all">
                    🧭 Calculate Route & ETA
                </button>
            </div>
        </div>

        <!-- Results Section -->
        <div id="resultsSection" class="hidden">
            <!-- ETA Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                    <p class="text-xs text-slate-400 mb-2">Distance</p>
                    <p id="distanceResult" class="text-3xl font-black text-blue-400">-</p>
                </div>

                <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                    <p class="text-xs text-slate-400 mb-2">Base ETA</p>
                    <p id="baseETA" class="text-3xl font-black text-emerald-400">-</p>
                </div>

                <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                    <p class="text-xs text-slate-400 mb-2">Weather Delay</p>
                    <p id="weatherDelay" class="text-3xl font-black text-amber-400">-</p>
                </div>

                <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                    <p class="text-xs text-slate-400 mb-2">Final ETA</p>
                    <p id="finalETA" class="text-3xl font-black text-red-400">-</p>
                </div>
            </div>

            <!-- Map Visualization -->
            <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl mb-6">
                <h2 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                    🗺️ Route Visualization
                </h2>
                <div id="routeMap"></div>
            </div>

            <!-- Cost Estimation -->
            <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                <h2 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                    💰 Cost Estimation
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-slate-950 p-4 rounded-lg">
                        <p class="text-xs text-slate-400 mb-1">Fuel Cost</p>
                        <p id="fuelCost" class="text-xl font-black text-emerald-400">-</p>
                    </div>
                    <div class="bg-slate-950 p-4 rounded-lg">
                        <p class="text-xs text-slate-400 mb-1">Port Fees</p>
                        <p id="portFees" class="text-xl font-black text-blue-400">-</p>
                    </div>
                    <div class="bg-slate-950 p-4 rounded-lg">
                        <p class="text-xs text-slate-400 mb-1">Total Estimated Cost</p>
                        <p id="totalCost" class="text-xl font-black text-amber-400">-</p>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script>
        let map;
        let originMarker, destinationMarker;
        let routeLine;
        let portsData = [];

        // Initialize map
        function initMap() {
            map = L.map('routeMap').setView([20, 0], 2);
            
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                subdomains: 'abcd',
                maxZoom: 19
            }).addTo(map);
        }

        // Load ports
        async function loadPorts() {
            try {
                console.log('🚢 Loading ports...');
                const response = await fetch('/api/ports?limit=1000');
                const data = await response.json();
                
                if (data.success) {
                    portsData = data.data;
                    populatePortSelects();
                    console.log('✅ Ports loaded:', portsData.length);
                } else {
                    console.error('❌ Failed to load ports');
                }
            } catch (error) {
                console.error('Error loading ports:', error);
            }
        }

        function populatePortSelects() {
            const originSelect = document.getElementById('originPort');
            const destSelect = document.getElementById('destinationPort');
            
            // Sort ports by name
            const sortedPorts = [...portsData].sort((a, b) => 
                a.port_name.localeCompare(b.port_name)
            );
            
            sortedPorts.forEach(port => {
                const option1 = document.createElement('option');
                option1.value = JSON.stringify({
                    name: port.port_name,
                    lat: port.latitude,
                    lon: port.longitude,
                    country: port.country_code
                });
                option1.textContent = `${port.port_name} (${port.country_code})`;
                originSelect.appendChild(option1);
                
                const option2 = option1.cloneNode(true);
                destSelect.appendChild(option2);
            });
        }

        // Update port info display
        document.getElementById('originPort').addEventListener('change', function() {
            if (this.value) {
                const port = JSON.parse(this.value);
                document.getElementById('originInfo').classList.remove('hidden');
                document.getElementById('originCoords').textContent = 
                    `📍 ${port.lat.toFixed(4)}°, ${port.lon.toFixed(4)}°`;
            } else {
                document.getElementById('originInfo').classList.add('hidden');
            }
        });

        document.getElementById('destinationPort').addEventListener('change', function() {
            if (this.value) {
                const port = JSON.parse(this.value);
                document.getElementById('destinationInfo').classList.remove('hidden');
                document.getElementById('destinationCoords').textContent = 
                    `📍 ${port.lat.toFixed(4)}°, ${port.lon.toFixed(4)}°`;
            } else {
                document.getElementById('destinationInfo').classList.add('hidden');
            }
        });

        // Calculate Haversine distance
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Earth radius in km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }

        // Calculate route and ETA
        async function calculateRoute() {
            const originSelect = document.getElementById('originPort');
            const destSelect = document.getElementById('destinationPort');
            const vesselSpeed = parseFloat(document.getElementById('vesselType').value);

            if (!originSelect.value || !destSelect.value) {
                alert('Please select both origin and destination ports');
                return;
            }

            const origin = JSON.parse(originSelect.value);
            const destination = JSON.parse(destSelect.value);

            console.log('🧭 Calculating route:', origin.name, '→', destination.name);

            // Calculate distance
            const distance = calculateDistance(
                origin.lat, origin.lon,
                destination.lat, destination.lon
            );

            // Calculate base ETA (hours)
            const baseETAHours = distance / vesselSpeed;
            const baseETADays = baseETAHours / 24;

            // Simulate weather delay (0-20% of base time)
            const weatherDelayPercent = Math.random() * 0.2; // 0-20%
            const weatherDelayDays = baseETADays * weatherDelayPercent;

            // Final ETA
            const finalETADays = baseETADays + weatherDelayDays;

            // Display results
            document.getElementById('distanceResult').textContent = distance.toFixed(0) + ' km';
            document.getElementById('baseETA').textContent = baseETADays.toFixed(2) + ' Hari';
            document.getElementById('weatherDelay').textContent = 
                weatherDelayDays > 0.1 ? weatherDelayDays.toFixed(2) + ' Hari' : 'None';
            document.getElementById('finalETA').textContent = finalETADays.toFixed(2) + ' Hari';

            // Calculate costs
            const fuelCostPerKm = 2.5; // USD per km (estimate)
            const fuelCost = distance * fuelCostPerKm;
            const portFees = 5000; // Fixed port fees
            const totalCost = fuelCost + portFees;

            document.getElementById('fuelCost').textContent = '$' + fuelCost.toLocaleString('en-US', {maximumFractionDigits: 0});
            document.getElementById('portFees').textContent = '$' + portFees.toLocaleString('en-US');
            document.getElementById('totalCost').textContent = '$' + totalCost.toLocaleString('en-US', {maximumFractionDigits: 0});

            // Show results
            document.getElementById('resultsSection').classList.remove('hidden');

            // Initialize map if not yet
            if (!map) {
                initMap();
            }

            // Clear previous markers and route
            if (originMarker) map.removeLayer(originMarker);
            if (destinationMarker) map.removeLayer(destinationMarker);
            if (routeLine) map.removeLayer(routeLine);

            // Add origin marker (green)
            originMarker = L.marker([origin.lat, origin.lon], {
                icon: L.divIcon({
                    html: '<div style="background: #10b981; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white;"></div>',
                    className: '',
                    iconSize: [20, 20]
                })
            }).addTo(map);
            originMarker.bindPopup(`<b>Origin:</b><br>${origin.name}<br>${origin.country}`);

            // Add destination marker (red)
            destinationMarker = L.marker([destination.lat, destination.lon], {
                icon: L.divIcon({
                    html: '<div style="background: #ef4444; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white;"></div>',
                    className: '',
                    iconSize: [20, 20]
                })
            }).addTo(map);
            destinationMarker.bindPopup(`<b>Destination:</b><br>${destination.name}<br>${destination.country}`);

            // Draw route line
            routeLine = L.polyline([
                [origin.lat, origin.lon],
                [destination.lat, destination.lon]
            ], {
                color: '#3b82f6',
                weight: 3,
                opacity: 0.7,
                dashArray: '10, 10'
            }).addTo(map);

            // Fit map to route
            const bounds = L.latLngBounds([
                [origin.lat, origin.lon],
                [destination.lat, destination.lon]
            ]);
            map.fitBounds(bounds, { padding: [50, 50] });

            // Scroll to results
            document.getElementById('resultsSection').scrollIntoView({ behavior: 'smooth' });
        }

        // Load ports on page load
        loadPorts();
    </script>

</body>
</html>
