<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Country Comparison - Supply Chain Risk</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #0f172a; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased">

    @include('partials.navbar')

    <main class="max-w-7xl mx-auto p-6 lg:p-8">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b border-slate-800 pb-4 gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-blue-500">⚖️ COUNTRY COMPARISON ENGINE</h1>
                <p class="text-slate-400 text-xs lg:text-sm mt-1">Bandingkan GDP, Inflasi, Risiko, Cuaca & Mata Uang Antar Negara</p>
            </div>
        </header>

        <!-- Country Selection -->
        <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl mb-6">
            <h2 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                🌍 Pilih Dua Negara untuk Dibandingkan
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <select id="country1" class="bg-slate-950 border border-slate-700 text-slate-200 px-4 py-3 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">-- Negara Pertama --</option>
                </select>
                <select id="country2" class="bg-slate-950 border border-slate-700 text-slate-200 px-4 py-3 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">-- Negara Kedua --</option>
                </select>
                <button onclick="compareCountries()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition-all">
                    🔍 Bandingkan Sekarang
                </button>
            </div>
        </div>

        <!-- Comparison Result -->
        <div id="comparisonResult" class="hidden">
            <!-- Basic Info Comparison -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                    <div class="flex items-center gap-4 mb-4">
                        <img id="flag1" src="" alt="Flag" class="w-16 h-12 object-cover rounded border border-slate-700">
                        <div>
                            <h2 id="name1" class="text-2xl font-black text-blue-400"></h2>
                            <p id="capital1" class="text-sm text-slate-400"></p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="bg-slate-950 p-3 rounded-lg flex justify-between">
                            <span class="text-slate-400">Populasi:</span>
                            <span id="pop1" class="font-bold text-slate-200"></span>
                        </div>
                        <div class="bg-slate-950 p-3 rounded-lg flex justify-between">
                            <span class="text-slate-400">Luas Area:</span>
                            <span id="area1" class="font-bold text-slate-200"></span>
                        </div>
                        <div class="bg-slate-950 p-3 rounded-lg flex justify-between">
                            <span class="text-slate-400">Region:</span>
                            <span id="region1" class="font-bold text-slate-200"></span>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                    <div class="flex items-center gap-4 mb-4">
                        <img id="flag2" src="" alt="Flag" class="w-16 h-12 object-cover rounded border border-slate-700">
                        <div>
                            <h2 id="name2" class="text-2xl font-black text-emerald-400"></h2>
                            <p id="capital2" class="text-sm text-slate-400"></p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="bg-slate-950 p-3 rounded-lg flex justify-between">
                            <span class="text-slate-400">Populasi:</span>
                            <span id="pop2" class="font-bold text-slate-200"></span>
                        </div>
                        <div class="bg-slate-950 p-3 rounded-lg flex justify-between">
                            <span class="text-slate-400">Luas Area:</span>
                            <span id="area2" class="font-bold text-slate-200"></span>
                        </div>
                        <div class="bg-slate-950 p-3 rounded-lg flex justify-between">
                            <span class="text-slate-400">Region:</span>
                            <span id="region2" class="font-bold text-slate-200"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Economic Comparison Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                    <h3 class="text-lg font-bold text-slate-200 mb-4">📊 GDP Comparison</h3>
                    <div class="bg-slate-950 p-4 rounded-lg">
                        <canvas id="gdpComparisonChart" height="200"></canvas>
                    </div>
                </div>

                <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                    <h3 class="text-lg font-bold text-slate-200 mb-4">📉 Inflation Comparison</h3>
                    <div class="bg-slate-950 p-4 rounded-lg">
                        <canvas id="inflationComparisonChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Weather & Risk Comparison -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                    <h3 class="text-lg font-bold text-slate-200 mb-4">🌤️ Weather Comparison</h3>
                    <div class="bg-slate-950 p-4 rounded-lg">
                        <canvas id="weatherComparisonChart" height="200"></canvas>
                    </div>
                </div>

                <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                    <h3 class="text-lg font-bold text-slate-200 mb-4">⚠️ Risk Score Comparison</h3>
                    <div class="bg-slate-950 p-4 rounded-lg">
                        <canvas id="riskComparisonChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Trade Comparison -->
            <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                <h3 class="text-lg font-bold text-slate-200 mb-4">💼 Export vs Import Comparison</h3>
                <div class="bg-slate-950 p-4 rounded-lg">
                    <canvas id="tradeComparisonChart" height="100"></canvas>
                </div>
            </div>
        </div>

    </main>

    <script>
        let charts = {};
        let countriesData = [];

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
                console.log('Loading countries for comparison...');
                const response = await fetch('/api/countries?limit=100');
                const data = await response.json();
                
                console.log('Countries response:', data);
                
                if (data.success) {
                    countriesData = data.data;
                    const selects = [document.getElementById('country1'), document.getElementById('country2')];
                    
                    selects.forEach(select => {
                        data.data.forEach(country => {
                            const option = document.createElement('option');
                            option.value = country.code;
                            option.textContent = country.name;
                            select.appendChild(option);
                        });
                    });
                    
                    console.log('Countries loaded:', data.data.length);
                } else {
                    console.error('Failed to load countries:', data.message);
                }
            } catch (error) {
                console.error('Error loading countries:', error);
            }
        }

        async function compareCountries() {
            const code1 = document.getElementById('country1').value;
            const code2 = document.getElementById('country2').value;

            if (!code1 || !code2) {
                console.log('Please select two countries');
                return;
            }

            if (code1 === code2) {
                console.log('Please select two different countries');
                return;
            }

            try {
                console.log('Comparing countries:', code1, code2);
                const [data1, data2] = await Promise.all([
                    fetch(`/api/countries/${code1}`).then(r => r.json()),
                    fetch(`/api/countries/${code2}`).then(r => r.json())
                ]);

                console.log('Comparison data1:', data1);
                console.log('Comparison data2:', data2);

                if (data1.success && data2.success) {
                    displayComparison(data1.data, data2.data);
                    document.getElementById('comparisonResult').classList.remove('hidden');
                    document.getElementById('comparisonResult').scrollIntoView({ behavior: 'smooth' });
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function displayComparison(country1, country2) {
            // Basic Info
            const flagUrl1 = country1.basic_info.flag || `https://flagcdn.com/w320/${country1.basic_info.code.toLowerCase()}.png`;
            document.getElementById('flag1').src = flagUrl1;
            document.getElementById('flag1').onerror = function() { this.src = 'https://via.placeholder.com/320x240?text=' + country1.basic_info.code; };
            document.getElementById('name1').textContent = country1.basic_info.name;
            document.getElementById('capital1').textContent = country1.basic_info.capital || 'N/A';
            document.getElementById('pop1').textContent = country1.basic_info.population ? formatNumber(country1.basic_info.population) : 'N/A';
            document.getElementById('area1').textContent = country1.basic_info.area ? formatNumber(country1.basic_info.area) + ' km²' : 'N/A';
            document.getElementById('region1').textContent = country1.basic_info.region || 'N/A';

            const flagUrl2 = country2.basic_info.flag || `https://flagcdn.com/w320/${country2.basic_info.code.toLowerCase()}.png`;
            document.getElementById('flag2').src = flagUrl2;
            document.getElementById('flag2').onerror = function() { this.src = 'https://via.placeholder.com/320x240?text=' + country2.basic_info.code; };
            document.getElementById('name2').textContent = country2.basic_info.name;
            document.getElementById('capital2').textContent = country2.basic_info.capital || 'N/A';
            document.getElementById('pop2').textContent = country2.basic_info.population ? formatNumber(country2.basic_info.population) : 'N/A';
            document.getElementById('area2').textContent = country2.basic_info.area ? formatNumber(country2.basic_info.area) + ' km²' : 'N/A';
            document.getElementById('region2').textContent = country2.basic_info.region || 'N/A';

            // GDP Comparison
            createComparisonChart('gdpComparisonChart', 'GDP Comparison', 
                [country1.basic_info.name, country2.basic_info.name],
                [country1.economic_data.gdp || 0, country2.economic_data.gdp || 0],
                '#10b981'
            );

            // Inflation Comparison
            createComparisonChart('inflationComparisonChart', 'Inflation Rate (%)', 
                [country1.basic_info.name, country2.basic_info.name],
                [country1.economic_data.inflation || 0, country2.economic_data.inflation || 0],
                '#f59e0b'
            );

            // Weather Comparison
            const weather1 = country1.weather || {};
            const weather2 = country2.weather || {};
            createMultiBarChart('weatherComparisonChart', 
                ['Temperature (°C)', 'Wind Speed (km/h)', 'Humidity (%)'],
                [
                    { label: country1.basic_info.name, data: [weather1.temperature_2m || 0, weather1.wind_speed_10m || 0, weather1.relative_humidity_2m || 0], color: '#3b82f6' },
                    { label: country2.basic_info.name, data: [weather2.temperature_2m || 0, weather2.wind_speed_10m || 0, weather2.relative_humidity_2m || 0], color: '#10b981' }
                ]
            );

            // Risk Comparison
            createComparisonChart('riskComparisonChart', 'Risk Score', 
                [country1.basic_info.name, country2.basic_info.name],
                [country1.risk_score?.total_score || 0, country2.risk_score?.total_score || 0],
                '#ef4444'
            );

            // Trade Comparison
            createMultiBarChart('tradeComparisonChart',
                [country1.basic_info.name + ' Export', country1.basic_info.name + ' Import', 
                 country2.basic_info.name + ' Export', country2.basic_info.name + ' Import'],
                [
                    { label: 'Trade Volume', data: [
                        country1.economic_data.exports || 0, 
                        country1.economic_data.imports || 0,
                        country2.economic_data.exports || 0, 
                        country2.economic_data.imports || 0
                    ], color: '#8b5cf6' }
                ]
            );
        }

        function createComparisonChart(canvasId, label, labels, data, color) {
            const ctx = document.getElementById(canvasId).getContext('2d');
            
            if (charts[canvasId]) {
                charts[canvasId].destroy();
            }

            charts[canvasId] = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        backgroundColor: [color, color + 'cc'],
                        borderColor: color,
                        borderWidth: 2
                    }]
                },
                options: chartOptions
            });
        }

        function createMultiBarChart(canvasId, labels, datasets) {
            const ctx = document.getElementById(canvasId).getContext('2d');
            
            if (charts[canvasId]) {
                charts[canvasId].destroy();
            }

            const chartDatasets = datasets.map(ds => ({
                label: ds.label,
                data: ds.data,
                backgroundColor: ds.color,
                borderColor: ds.color,
                borderWidth: 2
            }));

            charts[canvasId] = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: chartDatasets
                },
                options: chartOptions
            });
        }

        function formatNumber(num) {
            if (!num) return '0';
            return new Intl.NumberFormat('en-US').format(num);
        }

        // Load countries on page load
        loadCountries();
    </script>

</body>
</html>
