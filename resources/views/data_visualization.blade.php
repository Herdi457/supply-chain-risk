<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Visualization Dashboard - Supply Chain Risk</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #0f172a; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        #countryMap { height: 400px; width: 100%; border-radius: 12px; z-index: 1; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased">

    @include('partials.navbar')

    <main class="max-w-7xl mx-auto p-6 lg:p-8">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b border-slate-800 pb-4 gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-blue-500">📊 DATA VISUALIZATION DASHBOARD</h1>
                <p class="text-slate-400 text-xs lg:text-sm mt-1">Grafik Trend GDP, Inflasi, Kurs Mata Uang & Skor Risiko</p>
            </div>
        </header>

        <!-- Country Selection -->
        <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl mb-6">
            <h2 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                🌍 Pilih Negara untuk Analisis
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <select id="countrySelect" class="md:col-span-3 bg-slate-950 border border-slate-700 text-slate-200 px-4 py-3 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">-- Pilih Negara --</option>
                </select>
                <button onclick="loadVisualization()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition-all">
                    Tampilkan Data
                </button>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- GDP Trend -->
            <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                <h3 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                    📈 GDP Trend (5 Tahun)
                </h3>
                <div class="bg-slate-950 p-4 rounded-lg">
                    <canvas id="gdpChart" height="200"></canvas>
                </div>
            </div>

            <!-- Inflation Trend -->
            <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                <h3 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                    📉 Inflation Trend (5 Tahun)
                </h3>
                <div class="bg-slate-950 p-4 rounded-lg">
                    <canvas id="inflationChart" height="200"></canvas>
                </div>
            </div>

            <!-- Currency Trend -->
            <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                <h3 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                    💱 Currency Trend (vs USD)
                </h3>
                <div class="bg-slate-950 p-4 rounded-lg">
                    <canvas id="currencyChart" height="200"></canvas>
                </div>
            </div>

            <!-- Risk Trend -->
            <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                <h3 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                    ⚠️ Risk Score Trend
                </h3>
                <div class="bg-slate-950 p-4 rounded-lg">
                    <canvas id="riskChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Trade Balance Chart -->
        <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl mb-6">
            <h3 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                📊 Export vs Import Comparison
            </h3>
            <div class="bg-slate-950 p-4 rounded-lg">
                <canvas id="tradeChart" height="80"></canvas>
            </div>
        </div>

        <!-- Risk Distribution Pie Chart -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                <h3 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                    🎯 Risk Distribution
                </h3>
                <div class="bg-slate-950 p-4 rounded-lg">
                    <canvas id="riskPieChart" height="250"></canvas>
                </div>
            </div>

            <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                <h3 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                    📋 Data Summary
                </h3>
                <div id="dataSummary" class="space-y-3">
                    <div class="bg-slate-950 p-4 rounded-lg text-center text-slate-500">
                        Pilih negara untuk melihat ringkasan data
                    </div>
                </div>
            </div>
        </div>

        <!-- Country Map -->
        <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl mb-6">
            <h3 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                🗺️ Peta Lokasi Negara
            </h3>
            <div id="countryMap" class="bg-slate-950 border border-slate-800"></div>
        </div>

    </main>

    <script>
        let charts = {};
        let countriesData = [];

        // Chart config
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: { color: '#cbd5e1' }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#cbd5e1',
                    bodyColor: '#cbd5e1',
                    borderColor: '#334155',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    ticks: { color: '#64748b' },
                    grid: { color: '#1e293b' }
                },
                x: {
                    ticks: { color: '#64748b' },
                    grid: { color: '#1e293b' }
                }
            }
        };

        async function loadCountries() {
            try {
                console.log('Loading countries...');
                const response = await fetch('/api/countries?limit=300');
                const data = await response.json();
                
                console.log('Countries response:', data);
                
                if (data.success) {
                    countriesData = data.data;
                    const select = document.getElementById('countrySelect');
                    
                    data.data.forEach(country => {
                        const option = document.createElement('option');
                        option.value = country.code;
                        option.textContent = country.name;
                        select.appendChild(option);
                    });
                    
                    console.log('Countries loaded:', data.data.length);
                    
                    if (data.data.length > 0) {
                        const defaultCountry = data.data.find(c => c.code === 'ID') || data.data[0];
                        select.value = defaultCountry.code;
                        loadVisualization();
                    }
                } else {
                    console.error('Failed to load countries:', data.message);
                }
            } catch (error) {
                console.error('Error loading countries:', error);
            }
        }

        async function loadVisualization() {
            const countryCode = document.getElementById('countrySelect').value;
            if (!countryCode) {
                console.log('Please select a country first');
                return;
            }

            try {
                console.log('Loading visualization for:', countryCode);
                const response = await fetch(`/api/countries/${countryCode}`);
                const data = await response.json();

                console.log('Visualization response:', data);

                if (data.success) {
                    createCharts(data.data);
                    displaySummary(data.data);
                    updateCountryMap(data.data);
                } else {
                    console.error('Failed to load visualization:', data.message);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function createCharts(countryData) {
            // Generate dummy historical data for demo
            const years = [];
            for (let i = 4; i >= 0; i--) {
                years.push((new Date().getFullYear() - i).toString());
            }

            // GDP Trend Chart
            const gdpData = generateTrendData(countryData.economic_data.gdp || 1000000000, 5);
            createLineChart('gdpChart', 'GDP Trend', years, gdpData, '#10b981');

            // Inflation Trend Chart
            const inflationData = generateTrendData(countryData.economic_data.inflation || 3, 5, true);
            createLineChart('inflationChart', 'Inflation Rate (%)', years, inflationData, '#f59e0b');

            // Currency Trend Chart (simulated)
            const currencyData = generateTrendData(1.2, 5, true);
            createLineChart('currencyChart', 'Exchange Rate vs USD', years, currencyData, '#3b82f6');

            // Risk Trend Chart (simulated)
            const riskData = generateTrendData(countryData.risk_score?.total_score || 30, 5, true);
            createLineChart('riskChart', 'Risk Score', years, riskData, '#ef4444');

            // Trade Balance Chart
            createTradeChart(countryData.economic_data);

            // Risk Distribution Pie Chart
            createRiskPieChart(countryData.risk_score);
        }

        function createLineChart(canvasId, label, labels, data, color) {
            const ctx = document.getElementById(canvasId).getContext('2d');
            
            if (charts[canvasId]) {
                charts[canvasId].destroy();
            }

            charts[canvasId] = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        borderColor: color,
                        backgroundColor: color + '30',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: chartOptions
            });
        }

        function createTradeChart(economicData) {
            const ctx = document.getElementById('tradeChart').getContext('2d');
            
            if (charts.tradeChart) {
                charts.tradeChart.destroy();
            }

            const exports = economicData.exports || 50000000000;
            const imports = economicData.imports || 45000000000;

            charts.tradeChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Current Year'],
                    datasets: [
                        {
                            label: 'Exports',
                            data: [exports],
                            backgroundColor: '#10b981'
                        },
                        {
                            label: 'Imports',
                            data: [imports],
                            backgroundColor: '#ef4444'
                        }
                    ]
                },
                options: {
                    ...chartOptions,
                    scales: {
                        y: {
                            ticks: {
                                color: '#64748b',
                                callback: function(value) {
                                    return '$' + (value / 1000000000).toFixed(1) + 'B';
                                }
                            },
                            grid: { color: '#1e293b' }
                        },
                        x: {
                            ticks: { color: '#64748b' },
                            grid: { color: '#1e293b' }
                        }
                    }
                }
            });
        }

        function createRiskPieChart(riskScore) {
            const ctx = document.getElementById('riskPieChart').getContext('2d');
            
            if (charts.riskPieChart) {
                charts.riskPieChart.destroy();
            }

            // Simulate risk component breakdown
            const totalRisk = riskScore?.total_score || 30;
            const weatherRisk = (totalRisk * 0.3).toFixed(1);
            const inflationRisk = (totalRisk * 0.25).toFixed(1);
            const politicalRisk = (totalRisk * 0.30).toFixed(1);
            const currencyRisk = (totalRisk * 0.15).toFixed(1);

            charts.riskPieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Weather Risk', 'Inflation Risk', 'Political Risk', 'Currency Risk'],
                    datasets: [{
                        data: [weatherRisk, inflationRisk, politicalRisk, currencyRisk],
                        backgroundColor: ['#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'],
                        borderColor: '#0f172a',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#cbd5e1', padding: 15 }
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#cbd5e1',
                            bodyColor: '#cbd5e1',
                            borderColor: '#334155',
                            borderWidth: 1
                        }
                    }
                }
            });
        }

        function displaySummary(countryData) {
            const econ = countryData.economic_data || {};
            const summaryHtml = `
                <div class="bg-slate-950 p-4 rounded-lg">
                    <p class="text-sm text-slate-400 mb-1">GDP</p>
                    <p class="text-xl font-black text-emerald-400">${econ.gdp ? '$' + formatLargeNumber(econ.gdp) : '$1.0T'}</p>
                </div>
                <div class="bg-slate-950 p-4 rounded-lg">
                    <p class="text-sm text-slate-400 mb-1">Inflation Rate</p>
                    <p class="text-xl font-black text-amber-400">${econ.inflation ? econ.inflation.toFixed(2) + '%' : '3.5%'}</p>
                </div>
                <div class="bg-slate-950 p-4 rounded-lg">
                    <p class="text-sm text-slate-400 mb-1">Exports</p>
                    <p class="text-xl font-black text-blue-400">${econ.exports ? '$' + formatLargeNumber(econ.exports) : '$500B'}</p>
                </div>
                <div class="bg-slate-950 p-4 rounded-lg">
                    <p class="text-sm text-slate-400 mb-1">Imports</p>
                    <p class="text-xl font-black text-purple-400">${econ.imports ? '$' + formatLargeNumber(econ.imports) : '$450B'}</p>
                </div>
                <div class="bg-slate-950 p-4 rounded-lg">
                    <p class="text-sm text-slate-400 mb-1">Risk Score</p>
                    <p class="text-xl font-black text-red-400">${countryData.risk_score ? parseFloat(countryData.risk_score.total_score).toFixed(1) + '%' : '45.0%'}</p>
                </div>
            `;
            document.getElementById('dataSummary').innerHTML = summaryHtml;
        }

        function generateTrendData(baseValue, years, isPercentage = false) {
            let numericBase = parseFloat(baseValue);
            if (isNaN(numericBase)) {
                numericBase = isPercentage ? 5.0 : 1000000000.0;
            }
            const data = [];
            for (let i = 0; i < years; i++) {
                const variation = isPercentage ? (Math.random() - 0.5) * 2 : (Math.random() - 0.5) * numericBase * 0.2;
                data.push((numericBase + variation * i * 0.3).toFixed(2));
            }
            return data;
        }

        function formatLargeNumber(num) {
            if (num >= 1000000000000) return (num / 1000000000000).toFixed(2) + 'T';
            if (num >= 1000000000) return (num / 1000000000).toFixed(2) + 'B';
            if (num >= 1000000) return (num / 1000000).toFixed(2) + 'M';
            return num.toFixed(0);
        }

        let countryMap = null;

        function updateCountryMap(countryData) {
            const coords = countryData.basic_info.coordinates;
            if (!coords || coords.length < 2) {
                console.log('No coordinates available for country map');
                return;
            }

            const lat = coords[0];
            const lng = coords[1];

            if (countryMap) {
                countryMap.remove();
            }

            countryMap = L.map('countryMap', {
                minZoom: 2,
                maxZoom: 10,
                zoomSnap: 0.5,
                noWrap: true,
                maxBounds: [[-85, -180], [85, 180]],
                maxBoundsViscosity: 1.0
            }).setView([lat, lng], 4);

            document.getElementById('countryMap').style.background = '#020617';

            const darkTiles = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; OpenStreetMap &copy; CARTO',
                subdomains: 'abcd',
                noWrap: true,
                maxZoom: 19
            });

            const osmTiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                subdomains: 'abc',
                noWrap: true,
                maxZoom: 19
            });

            darkTiles.addTo(countryMap);
            darkTiles.on('tileerror', function() {
                if (!countryMap.hasLayer(osmTiles)) {
                    countryMap.removeLayer(darkTiles);
                    osmTiles.addTo(countryMap);
                }
            });

            const portIcon = L.icon({
                iconUrl: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMiIgaGVpZ2h0PSIzMiIgdmlld0JveD0iMCAwIDMyIDMyIj48Y2lyY2xlIGN4PSIxNiIgY3k9IjE2IiByPSIxMiIgZmlsbD0iIzM4OTZmZiIgc3Ryb2tlPSIjZmZmIiBzdHJva2Utd2lkdGg9IjIiLz48Y2lyY2xlIGN4PSIxNiIgY3k9IjE2IiByPSI2IiBmaWxsPSIjZmZmIi8+PC9zdmc+',
                iconSize: [28, 28],
                iconAnchor: [14, 14],
                popupAnchor: [0, -14]
            });

            const marker = L.marker([lat, lng], { icon: portIcon }).addTo(countryMap);
            marker.bindPopup(`
                <div style="min-width: 200px; font-family: system-ui, -apple-system, sans-serif;">
                    <div style="border-bottom: 2px solid #3b82f6; padding-bottom: 8px; margin-bottom: 10px;">
                        <strong style="color: #3b82f6; font-size: 16px;">${countryData.basic_info.name}</strong>
                    </div>
                    <div style="font-size: 13px; color: #cbd5e1;">
                        <div style="margin-bottom: 6px;"><strong>Code:</strong> ${countryData.basic_info.code}</div>
                        <div style="margin-bottom: 6px;"><strong>Region:</strong> ${countryData.basic_info.region || 'N/A'}</div>
                        <div style="margin-bottom: 6px;"><strong>Coordinates:</strong> [${lat.toFixed(4)}, ${lng.toFixed(4)}]</div>
                    </div>
                </div>
            `);

            setTimeout(() => countryMap.invalidateSize(), 100);
        }

        // Load countries on page load
        loadCountries();
    </script>

</body>
</html>
