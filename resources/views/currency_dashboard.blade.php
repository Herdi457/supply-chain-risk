<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Impact Dashboard - Supply Chain Risk</title>
    
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
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-blue-500">💵 CURRENCY IMPACT DASHBOARD</h1>
                <p class="text-slate-400 text-xs lg:text-sm mt-1">Monitoring Real-time Nilai Tukar Mata Uang & Tren Perubahan</p>
            </div>
        </header>

        <!-- Currency Converter -->
        <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl mb-6">
            <h2 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                🔄 Konverter Mata Uang
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-sm text-slate-400 mb-2">Mata Uang Dasar</label>
                    <select id="baseCurrency" class="w-full bg-slate-950 border border-slate-700 text-slate-200 px-4 py-3 rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="USD">USD - US Dollar</option>
                        <option value="EUR">EUR - Euro</option>
                        <option value="GBP">GBP - British Pound</option>
                        <option value="JPY">JPY - Japanese Yen</option>
                        <option value="CNY">CNY - Chinese Yuan</option>
                        <option value="IDR">IDR - Indonesian Rupiah</option>
                        <option value="SGD">SGD - Singapore Dollar</option>
                        <option value="AUD">AUD - Australian Dollar</option>
                        <option value="CAD">CAD - Canadian Dollar</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-slate-400 mb-2">Jumlah</label>
                    <input type="number" id="amount" value="1" min="0" step="0.01"
                        class="w-full bg-slate-950 border border-slate-700 text-slate-200 px-4 py-3 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <button onclick="loadCurrencyData()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition-all">
                    Ambil Data Kurs
                </button>
            </div>
        </div>

        <!-- Current Rates -->
        <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-slate-200 flex items-center gap-2">
                    💱 Nilai Tukar Terkini
                </h2>
                <span id="lastUpdate" class="text-xs text-slate-400"></span>
            </div>
            <div id="currencyRates" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Will be filled by JavaScript -->
            </div>
        </div>

        <!-- Currency Trend Chart -->
        <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl mb-6">
            <h2 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                📈 Tren Nilai Tukar (7 Hari Terakhir)
            </h2>
            <div class="bg-slate-950 p-4 rounded-lg">
                <canvas id="currencyChart" height="80"></canvas>
            </div>
            <p class="text-xs text-slate-500 mt-2">* Data simulasi untuk demo. Integrasi dengan historical API untuk data real-time.</p>
        </div>

        <!-- Top Currencies -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                <h2 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                    🔝 Mata Uang Terkuat (vs USD)
                </h2>
                <div id="strongestCurrencies" class="space-y-3">
                    <!-- Will be filled by JavaScript -->
                </div>
            </div>

            <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                <h2 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                    📉 Mata Uang Terlemah (vs USD)
                </h2>
                <div id="weakestCurrencies" class="space-y-3">
                    <!-- Will be filled by JavaScript -->
                </div>
            </div>
        </div>

    </main>

    <script>
        let currencyChart = null;
        let currentRates = {};

        const majorCurrencies = ['EUR', 'GBP', 'JPY', 'CNY', 'IDR', 'SGD', 'AUD', 'CAD', 'CHF', 'INR', 'KRW', 'MXN'];

        async function loadCurrencyData() {
            const baseCurrency = document.getElementById('baseCurrency').value;
            
            try {
                const response = await fetch(`/api/currency?base=${baseCurrency}`);
                const data = await response.json();

                if (data.success) {
                    currentRates = data.data.rates;
                    document.getElementById('lastUpdate').textContent = `Updated: ${new Date(data.data.last_updated).toLocaleString()}`;
                    
                    displayCurrencyRates(baseCurrency);
                    displayTopCurrencies();
                    createCurrencyChart(baseCurrency);
                } else {
                    alert(data.message || 'Failed to fetch currency data');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error loading currency data. Please make sure EXCHANGERATE_API_KEY is set in .env file.');
            }
        }

        function displayCurrencyRates(base) {
            const amount = parseFloat(document.getElementById('amount').value) || 1;
            const ratesHtml = majorCurrencies.map(currency => {
                if (currency === base) return '';
                const rate = currentRates[currency];
                if (!rate) return '';

                const converted = (amount * rate).toFixed(2);
                
                return `
                    <div class="bg-slate-950 p-4 rounded-lg hover:bg-slate-800 transition-all">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-lg font-bold text-blue-400">${currency}</span>
                            <span class="text-xs text-slate-500">${base} → ${currency}</span>
                        </div>
                        <p class="text-2xl font-black text-slate-200">${formatNumber(converted)}</p>
                        <p class="text-xs text-slate-400 mt-1">Rate: ${rate.toFixed(4)}</p>
                    </div>
                `;
            }).join('');

            document.getElementById('currencyRates').innerHTML = ratesHtml || '<p class="text-slate-500">No data available</p>';
        }

        function displayTopCurrencies() {
            const sortedRates = Object.entries(currentRates)
                .filter(([currency]) => majorCurrencies.includes(currency))
                .sort((a, b) => a[1] - b[1]); // Ascending for strongest

            // Strongest (lowest rate means strongest vs base)
            const strongest = sortedRates.slice(0, 5);
            const strongestHtml = strongest.map(([currency, rate], index) => `
                <div class="bg-slate-950 p-3 rounded-lg flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <span class="text-lg font-black text-emerald-400">#${index + 1}</span>
                        <span class="font-bold text-slate-200">${currency}</span>
                    </div>
                    <span class="text-slate-400">${rate.toFixed(4)}</span>
                </div>
            `).join('');
            document.getElementById('strongestCurrencies').innerHTML = strongestHtml;

            // Weakest (highest rate means weakest vs base)
            const weakest = sortedRates.slice(-5).reverse();
            const weakestHtml = weakest.map(([currency, rate], index) => `
                <div class="bg-slate-950 p-3 rounded-lg flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <span class="text-lg font-black text-red-400">#${index + 1}</span>
                        <span class="font-bold text-slate-200">${currency}</span>
                    </div>
                    <span class="text-slate-400">${rate.toFixed(4)}</span>
                </div>
            `).join('');
            document.getElementById('weakestCurrencies').innerHTML = weakestHtml;
        }

        function createCurrencyChart(base) {
            const ctx = document.getElementById('currencyChart').getContext('2d');
            
            // Generate dummy historical data for demo
            const labels = [];
            const datasets = [];
            
            for (let i = 6; i >= 0; i--) {
                const date = new Date();
                date.setDate(date.getDate() - i);
                labels.push(date.toLocaleDateString('id-ID', { month: 'short', day: 'numeric' }));
            }

            // Add trend lines for top 3 currencies
            const topCurrencies = ['EUR', 'GBP', 'JPY'].filter(c => c !== base && currentRates[c]);
            const colors = ['#3b82f6', '#10b981', '#f59e0b'];

            topCurrencies.forEach((currency, index) => {
                const baseRate = currentRates[currency];
                const data = [];
                for (let i = 0; i < 7; i++) {
                    // Simulate variation ±3%
                    const variation = (Math.random() - 0.5) * 0.06;
                    data.push((baseRate * (1 + variation)).toFixed(4));
                }

                datasets.push({
                    label: `${base}/${currency}`,
                    data: data,
                    borderColor: colors[index],
                    backgroundColor: colors[index] + '20',
                    tension: 0.4,
                    fill: true
                });
            });

            if (currencyChart) {
                currencyChart.destroy();
            }

            currencyChart = new Chart(ctx, {
                type: 'line',
                data: { labels, datasets },
                options: {
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
                }
            });
        }

        function formatNumber(num) {
            return new Intl.NumberFormat('en-US').format(num);
        }

        // Load initial data
        loadCurrencyData();

        // Auto-refresh currency data setiap 5 menit untuk real-time
        setInterval(async () => {
            try {
                await loadCurrencyData();
                console.log('🔄 Currency data auto-refreshed');
            } catch (err) {
                console.error('Gagal auto-refresh currency:', err);
            }
        }, 300000); // 5 menit
    </script>

</body>
</html>
