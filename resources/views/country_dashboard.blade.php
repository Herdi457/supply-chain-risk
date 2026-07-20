<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Country Dashboard - Supply Chain Risk</title>
    
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
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-blue-500">🏳️ GLOBAL COUNTRY DASHBOARD</h1>
                <p class="text-slate-400 text-xs lg:text-sm mt-1">Monitoring Komprehensif Data Ekonomi, Demografi & Cuaca Negara</p>
            </div>
        </header>

        <!-- Search Country Section -->
        <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl mb-6">
            <h2 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                🔍 Pilih Negara untuk Analisis
            </h2>
            <div class="flex gap-4">
                <input type="text" id="searchCountry" 
                    class="flex-1 bg-slate-950 border border-slate-700 text-slate-200 px-4 py-3 rounded-lg focus:outline-none focus:border-blue-500 transition-all"
                    placeholder="Ketik nama negara, ibukota, atau kode... (contoh: Indonesia, Berlin, US)">
                <button onclick="searchCountry()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition-all">
                    Cari Negara
                </button>
                <button onclick="showAllCountries()" 
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg font-bold transition-all whitespace-nowrap">
                    Tampilkan Semua
                </button>
            </div>
            <div class="mt-2 text-sm text-slate-400 flex justify-between items-center">
                <span id="countryCount">Loading...</span>
                <span class="text-xs">Scroll untuk melihat lebih banyak negara</span>
            </div>
            <div id="countryList" class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 max-h-96 overflow-y-auto custom-scrollbar"></div>
        </div>

        <!-- Country Detail Section -->
        <div id="countryDetail" class="hidden">
            <!-- Basic Info Card -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                    <div class="flex items-center gap-4 mb-4">
                        <div id="countryFlagContainer" class="w-16 h-12 flex items-center justify-center bg-slate-950 rounded border border-slate-700">
                            <span id="countryFlagEmoji" class="text-3xl">🌍</span>
                            <img id="countryFlag" src="" alt="Flag" class="hidden w-16 h-12 object-cover rounded border border-slate-700">
                        </div>
                        <div>
                            <h2 id="countryName" class="text-xl font-black text-blue-400"></h2>
                            <p id="countryCapital" class="text-sm text-slate-400"></p>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Kode:</span>
                            <span id="countryCode" class="font-bold text-slate-200"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Region:</span>
                            <span id="countryRegion" class="font-bold text-slate-200"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Populasi:</span>
                            <span id="countryPopulation" class="font-bold text-slate-200"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Luas:</span>
                            <span id="countryArea" class="font-bold text-slate-200"></span>
                        </div>
                    </div>
                </div>

                <!-- Economic Data Card -->
                <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                    <h3 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                        📊 Data Ekonomi
                        <span class="text-[10px] text-slate-500 font-normal">(Sumber: World Bank API)</span>
                    </h3>
                    <div class="space-y-3">
                        <div class="bg-slate-950 p-3 rounded-lg">
                            <p class="text-xs text-slate-400 mb-1">GDP (Tahun Lalu)</p>
                            <p id="countryGDP" class="text-xl font-black text-emerald-400">-</p>
                        </div>
                        <div class="bg-slate-950 p-3 rounded-lg">
                            <p class="text-xs text-slate-400 mb-1">Inflasi (%)</p>
                            <p id="countryInflation" class="text-xl font-black text-amber-400">-</p>
                        </div>
                        <div class="bg-slate-950 p-3 rounded-lg">
                            <p class="text-xs text-slate-400 mb-1">Ekspor</p>
                            <p id="countryExports" class="text-xl font-black text-blue-400">-</p>
                        </div>
                        <div class="bg-slate-950 p-3 rounded-lg">
                            <p class="text-xs text-slate-400 mb-1">Impor</p>
                            <p id="countryImports" class="text-xl font-black text-purple-400">-</p>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-800">
                        <p class="text-[9px] text-slate-500 flex items-center gap-1">
                            <span class="bg-amber-900/30 text-amber-400 px-1.5 py-0.5 rounded">EST</span>
                            = Data estimasi berbasis populasi & region
                        </p>
                    </div>
                </div>

                <!-- Weather Card -->
                <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                    <h3 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                        🌤️ Cuaca Saat Ini
                    </h3>
                    <div class="space-y-3">
                        <div class="bg-slate-950 p-3 rounded-lg">
                            <p class="text-xs text-slate-400 mb-1">Suhu</p>
                            <p id="weatherTemp" class="text-2xl font-black text-orange-400">-</p>
                        </div>
                        <div class="bg-slate-950 p-3 rounded-lg">
                            <p class="text-xs text-slate-400 mb-1">Curah Hujan</p>
                            <p id="weatherRain" class="text-xl font-black text-blue-300">-</p>
                        </div>
                        <div class="bg-slate-950 p-3 rounded-lg">
                            <p class="text-xs text-slate-400 mb-1">Kecepatan Angin</p>
                            <p id="weatherWind" class="text-xl font-black text-cyan-400">-</p>
                        </div>
                        <div class="bg-slate-950 p-3 rounded-lg">
                            <p class="text-xs text-slate-400 mb-1">Kelembaban</p>
                            <p id="weatherHumidity" class="text-xl font-black text-teal-400">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Currency & Languages -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                    <h3 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                        💵 Mata Uang
                    </h3>
                    <div id="countryCurrencies" class="space-y-2"></div>
                </div>

                <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                    <h3 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                        🗣️ Bahasa
                    </h3>
                    <div id="countryLanguages" class="space-y-2"></div>
                </div>
            </div>

            <!-- Risk Score Card -->
            <div id="riskScoreCard" class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl hidden">
                <h3 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                    ⚠️ Risk Score
                </h3>
                <div class="flex items-center gap-6">
                    <div class="text-center">
                        <p id="riskScore" class="text-5xl font-black text-red-400"></p>
                        <p class="text-xs text-slate-400 mt-2">Total Risk Score</p>
                    </div>
                    <div class="flex-1">
                        <p id="riskLevel" class="text-2xl font-bold mb-2"></p>
                        <p id="riskUpdated" class="text-xs text-slate-400"></p>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script>
        let countriesData = [];

        // Load initial countries
        async function loadCountries() {
            try {
                console.log('Loading countries...');
                const response = await fetch('/api/countries?limit=300');
                const data = await response.json();
                console.log('Countries response:', data);
                if (data.success) {
                    countriesData = data.data;
                    // Sort alphabetically
                    countriesData.sort((a, b) => a.name.localeCompare(b.name));
                    displayCountries(countriesData.slice(0, 30));
                    console.log('Countries loaded:', data.data.length);
                } else {
                    console.error('Failed to load countries:', data.message);
                }
            } catch (error) {
                console.error('Error loading countries:', error);
            }
        }

        function displayCountries(countries) {
            const countryList = document.getElementById('countryList');
            const countryCount = document.getElementById('countryCount');
            
            countryList.innerHTML = countries.map(country => `
                <div onclick="selectCountry('${country.code}')" 
                    class="bg-slate-950 hover:bg-slate-800 border border-slate-800 hover:border-blue-600 p-3 rounded-lg cursor-pointer transition-all flex items-center gap-3">
                    <img src="${country.flag}" alt="${country.name}" class="w-8 h-6 object-cover rounded" 
                         onerror="this.style.display='none'">
                    <div>
                        <p class="font-bold text-sm text-slate-200">${country.name}</p>
                        <p class="text-xs text-slate-400">${country.capital || '-'} • ${country.code}</p>
                    </div>
                </div>
            `).join('');
            
            countryCount.innerHTML = `Menampilkan <strong>${countries.length}</strong> dari <strong>${countriesData.length}</strong> negara`;
        }
        
        function showAllCountries() {
            document.getElementById('searchCountry').value = '';
            displayCountries(countriesData);
        }

        function searchCountry() {
            const query = document.getElementById('searchCountry').value.toLowerCase();
            if (!query) {
                displayCountries(countriesData.slice(0, 30));
                return;
            }

            const filtered = countriesData.filter(country => 
                country.name.toLowerCase().includes(query) || 
                (country.capital && country.capital.toLowerCase().includes(query)) ||
                country.code.toLowerCase().includes(query)
            );
            // Show all filtered results, no limit
            displayCountries(filtered);
            
            // Show count message
            if (filtered.length === 0) {
                document.getElementById('countryList').innerHTML = '<div class="col-span-full text-center text-slate-400 py-4">Tidak ada negara yang cocok dengan pencarian</div>';
            }
        }

        async function selectCountry(code) {
            try {
                console.log('🎯 Selecting country:', code);
                currentCountryCode = code; // Track current country for auto-refresh
                document.getElementById('countryDetail').classList.remove('hidden');
                
                // Scroll to detail
                document.getElementById('countryDetail').scrollIntoView({ behavior: 'smooth' });

                console.log('🎯 Fetching /api/countries/' + code);
                const response = await fetch(`/api/countries/${code}`);
                console.log('🎯 Response status:', response.status);
                const data = await response.json();

                console.log('🎯 Country detail response:', data);

                if (data.success) {
                    const country = data.data;

                    // Basic Info
                    const flagUrl = country.basic_info.flag || `https://flagcdn.com/w320/${country.basic_info.code.toLowerCase()}.png`;
                    const flagImg = document.getElementById('countryFlag');
                    const flagEmoji = document.getElementById('countryFlagEmoji');
                    
                    flagImg.src = flagUrl;
                    flagImg.onload = function() {
                        flagImg.classList.remove('hidden');
                        flagEmoji.classList.add('hidden');
                    };
                    flagImg.onerror = function() { 
                        this.src = `https://flagcdn.com/w320/${country.basic_info.code.toLowerCase()}.png`;
                        // Jika gagal lagi, tetap show emoji
                        setTimeout(() => {
                            if (this.complete && this.naturalHeight === 0) {
                                flagImg.classList.add('hidden');
                                flagEmoji.classList.remove('hidden');
                            }
                        }, 1000);
                    };
                    
                    document.getElementById('countryName').textContent = country.basic_info.name;
                    document.getElementById('countryCapital').textContent = country.basic_info.capital || 'N/A';
                    document.getElementById('countryCode').textContent = country.basic_info.code;
                    document.getElementById('countryRegion').textContent = country.basic_info.region || 'N/A';
                    document.getElementById('countryPopulation').textContent = country.basic_info.population ? formatNumber(country.basic_info.population) : 'N/A';
                    document.getElementById('countryArea').textContent = country.basic_info.area ? formatNumber(country.basic_info.area) + ' km²' : 'N/A';

                    // Economic Data
                    const econ = country.economic_data;
                    const isEstimated = econ.estimated ? '<span class="text-[9px] bg-amber-900/30 text-amber-400 px-1.5 py-0.5 rounded ml-1">EST</span>' : '';
                    document.getElementById('countryGDP').innerHTML = econ.gdp ? '$' + formatNumber(econ.gdp) + isEstimated : 'N/A';
                    document.getElementById('countryInflation').innerHTML = econ.inflation ? econ.inflation.toFixed(2) + '%' + isEstimated : 'N/A';
                    document.getElementById('countryExports').innerHTML = econ.exports ? '$' + formatNumber(econ.exports) + isEstimated : 'N/A';
                    document.getElementById('countryImports').innerHTML = econ.imports ? '$' + formatNumber(econ.imports) + isEstimated : 'N/A';

                    // Weather
                    const weather = country.weather;
                    if (weather) {
                        document.getElementById('weatherTemp').textContent = weather.temperature_2m + '°C';
                        document.getElementById('weatherRain').textContent = weather.precipitation + ' mm';
                        document.getElementById('weatherWind').textContent = weather.wind_speed_10m + ' km/h';
                        document.getElementById('weatherHumidity').textContent = weather.relative_humidity_2m + '%';
                    }

                    // Currencies
                    const currenciesHtml = Object.entries(country.currencies).map(([code, curr]) => `
                        <div class="bg-slate-950 p-3 rounded-lg flex justify-between items-center">
                            <span class="font-bold text-slate-200">${curr.name}</span>
                            <span class="text-slate-400">${curr.symbol || code}</span>
                        </div>
                    `).join('');
                    document.getElementById('countryCurrencies').innerHTML = currenciesHtml;

                    // Languages
                    const languagesHtml = Object.values(country.languages).map(lang => `
                        <div class="bg-slate-950 p-3 rounded-lg">
                            <span class="font-bold text-slate-200">${lang}</span>
                        </div>
                    `).join('');
                    document.getElementById('countryLanguages').innerHTML = languagesHtml;

                    // Risk Score
                    if (country.risk_score) {
                        document.getElementById('riskScoreCard').classList.remove('hidden');
                        const totalScore = parseFloat(country.risk_score.total_score);
                        document.getElementById('riskScore').textContent = (isNaN(totalScore) ? '0' : totalScore.toFixed(1)) + '%';
                        document.getElementById('riskLevel').textContent = country.risk_score.level;
                        document.getElementById('riskUpdated').textContent = 'Last updated: ' + new Date(country.risk_score.last_updated).toLocaleString();
                        
                        // Color based on level
                        const levelEl = document.getElementById('riskLevel');
                        if (country.risk_score.level === 'High Risk') {
                            levelEl.className = 'text-2xl font-bold mb-2 text-red-400';
                        } else if (country.risk_score.level === 'Medium Risk') {
                            levelEl.className = 'text-2xl font-bold mb-2 text-amber-400';
                        } else {
                            levelEl.className = 'text-2xl font-bold mb-2 text-emerald-400';
                        }
                    } else {
                        document.getElementById('riskScoreCard').classList.add('hidden');
                    }
                }
            } catch (error) {
                console.error('Error loading country detail:', error);
            }
        }

        function formatNumber(num) {
            if (!num) return '0';
            return new Intl.NumberFormat('en-US').format(num);
        }

        // Auto search on Enter
        document.getElementById('searchCountry').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                searchCountry();
            } else {
                // Live search while typing (debounced)
                clearTimeout(window.searchTimeout);
                window.searchTimeout = setTimeout(() => {
                    searchCountry();
                }, 300); // 300ms delay after user stops typing
            }
        });

        // Load countries on page load
        loadCountries();

        // Auto-refresh weather data setiap 10 menit untuk real-time
        let currentCountryCode = null;
        setInterval(async () => {
            if (currentCountryCode) {
                try {
                    const response = await fetch(`/api/countries/${currentCountryCode}`);
                    const data = await response.json();
                    if (data.success && data.data.weather) {
                        const weather = data.data.weather;
                        document.getElementById('weatherTemp').textContent = weather.temperature_2m + '°C';
                        document.getElementById('weatherRain').textContent = weather.precipitation + ' mm';
                        document.getElementById('weatherWind').textContent = weather.wind_speed_10m + ' km/h';
                        document.getElementById('weatherHumidity').textContent = weather.relative_humidity_2m + '%';
                        console.log('🔄 Weather data refreshed for', currentCountryCode);
                    }
                } catch (err) {
                    console.error('Gagal refresh weather:', err);
                }
            }
        }, 600000); // 10 menit
    </script>

</body>
</html>
