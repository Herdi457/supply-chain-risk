<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Intelligence Dashboard - Supply Chain Risk</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
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
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-blue-500">📰 NEWS INTELLIGENCE DASHBOARD</h1>
                <p class="text-slate-400 text-xs lg:text-sm mt-1">Berita Terkini Seputar Logistik, Perdagangan & Ekonomi Global</p>
            </div>
        </header>

        <!-- News Category Filter -->
        <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl mb-6">
            <h2 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                🔍 Kategori Berita
            </h2>
            <div class="flex flex-wrap gap-3">
                <button onclick="loadNews('supply chain')" class="category-btn bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold transition-all">
                    Supply Chain
                </button>
                <button onclick="loadNews('logistics')" class="category-btn bg-slate-700 hover:bg-slate-600 text-slate-200 px-4 py-2 rounded-lg font-bold transition-all">
                    Logistics
                </button>
                <button onclick="loadNews('trade')" class="category-btn bg-slate-700 hover:bg-slate-600 text-slate-200 px-4 py-2 rounded-lg font-bold transition-all">
                    Trade
                </button>
                <button onclick="loadNews('shipping')" class="category-btn bg-slate-700 hover:bg-slate-600 text-slate-200 px-4 py-2 rounded-lg font-bold transition-all">
                    Shipping
                </button>
                <button onclick="loadNews('economy')" class="category-btn bg-slate-700 hover:bg-slate-600 text-slate-200 px-4 py-2 rounded-lg font-bold transition-all">
                    Economy
                </button>
                <button onclick="loadNews('inflation')" class="category-btn bg-slate-700 hover:bg-slate-600 text-slate-200 px-4 py-2 rounded-lg font-bold transition-all">
                    Inflation
                </button>
                <button onclick="loadNews('port congestion')" class="category-btn bg-slate-700 hover:bg-slate-600 text-slate-200 px-4 py-2 rounded-lg font-bold transition-all">
                    Port Congestion
                </button>
            </div>
        </div>

        <!-- Sentiment Analysis Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                <div class="flex items-center gap-4">
                    <div class="bg-emerald-500/20 p-4 rounded-lg">
                        <span class="text-3xl">😊</span>
                    </div>
                    <div>
                        <p class="text-sm text-slate-400">Positive News</p>
                        <p id="positiveCount" class="text-3xl font-black text-emerald-400">0</p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                <div class="flex items-center gap-4">
                    <div class="bg-slate-500/20 p-4 rounded-lg">
                        <span class="text-3xl">😐</span>
                    </div>
                    <div>
                        <p class="text-sm text-slate-400">Neutral News</p>
                        <p id="neutralCount" class="text-3xl font-black text-slate-400">0</p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
                <div class="flex items-center gap-4">
                    <div class="bg-red-500/20 p-4 rounded-lg">
                        <span class="text-3xl">😟</span>
                    </div>
                    <div>
                        <p class="text-sm text-slate-400">Negative News</p>
                        <p id="negativeCount" class="text-3xl font-black text-red-400">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- News Grid -->
        <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-slate-200 flex items-center gap-2">
                    📄 Berita Terbaru
                </h2>
                <div id="loadingIndicator" class="hidden">
                    <span class="text-sm text-blue-400 animate-pulse">Loading...</span>
                </div>
            </div>
            
            <div id="newsContainer" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Will be filled by JavaScript -->
            </div>
        </div>

    </main>

    <script>
        let currentTopic = 'supply chain';
        
        // Lexicon-based sentiment analysis (simple version)
        const positiveWords = ['growth', 'increase', 'profit', 'stable', 'improve', 'success', 'gain', 'rise', 'strong', 'positive', 'boost', 'expansion'];
        const negativeWords = ['war', 'crisis', 'inflation', 'delay', 'disaster', 'decline', 'fall', 'loss', 'risk', 'threat', 'conflict', 'shortage'];

        async function loadNews(topic) {
            currentTopic = topic;
            document.getElementById('loadingIndicator').classList.remove('hidden');
            
            // Update active category button
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'bg-blue-700');
                btn.classList.add('bg-slate-700');
            });
            if (event && event.target) {
                event.target.classList.remove('bg-slate-700');
                event.target.classList.add('bg-blue-600');
            }

            try {
                console.log('🔄 Loading news for topic:', topic);
                const response = await fetch(`/api/news?topic=${encodeURIComponent(topic)}&limit=10`);
                const data = await response.json();
                console.log('📊 News API response:', data);

                if (data.success) {
                    displayNews(data.data);
                    analyzeSentiment(data.data);
                } else {
                    document.getElementById('newsContainer').innerHTML = `
                        <div class="col-span-2 text-center py-12 text-slate-500">
                            <p class="text-xl mb-2">⚠️</p>
                            <p>${data.message}</p>
                            <p class="text-xs mt-2">Please set GNEWS_API_KEY in your .env file</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading news:', error);
                document.getElementById('newsContainer').innerHTML = `
                    <div class="col-span-2 text-center py-12 text-red-400">
                        <p class="text-xl mb-2">❌</p>
                        <p>Error loading news. Please try again later.</p>
                    </div>
                `;
            } finally {
                document.getElementById('loadingIndicator').classList.add('hidden');
            }
        }

        function displayNews(articles) {
            if (!articles || articles.length === 0) {
                document.getElementById('newsContainer').innerHTML = `
                    <div class="col-span-2 text-center py-12 text-slate-500">
                        <p class="text-xl mb-2">📭</p>
                        <p>No news found for this topic</p>
                    </div>
                `;
                return;
            }

            const newsHtml = articles.map(article => {
                const sentiment = getSentiment(article.title + ' ' + (article.description || ''));
                const sentimentBadge = getSentimentBadge(sentiment);
                
                return `
                    <div class="bg-slate-950 border border-slate-800 rounded-lg overflow-hidden hover:border-blue-600 transition-all">
                        ${article.image ? `
                            <img src="${article.image}" alt="${article.title}" 
                                class="w-full h-48 object-cover"
                                onerror="this.style.display='none'">
                        ` : ''}
                        <div class="p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xs text-slate-500">${article.source.name || 'Unknown'}</span>
                                ${sentimentBadge}
                            </div>
                            <h3 class="text-lg font-bold text-slate-200 mb-2 line-clamp-2">
                                ${article.title}
                            </h3>
                            <p class="text-sm text-slate-400 mb-4 line-clamp-3">
                                ${article.description || 'No description available'}
                            </p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-slate-500">
                                    ${new Date(article.publishedAt).toLocaleDateString('id-ID')}
                                </span>
                                <a href="${article.url}" target="_blank" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-xs font-bold transition-all">
                                    Read More →
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            document.getElementById('newsContainer').innerHTML = newsHtml;
        }

        function getSentiment(text) {
            const words = text.toLowerCase().split(/\s+/);
            let positiveScore = 0;
            let negativeScore = 0;

            words.forEach(word => {
                if (positiveWords.includes(word)) positiveScore++;
                if (negativeWords.includes(word)) negativeScore++;
            });

            if (positiveScore > negativeScore) return 'positive';
            if (negativeScore > positiveScore) return 'negative';
            return 'neutral';
        }

        function getSentimentBadge(sentiment) {
            const badges = {
                positive: '<span class="text-xs px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">😊 Positive</span>',
                negative: '<span class="text-xs px-2 py-0.5 rounded bg-red-500/20 text-red-400 border border-red-500/30">😟 Negative</span>',
                neutral: '<span class="text-xs px-2 py-0.5 rounded bg-slate-500/20 text-slate-400 border border-slate-500/30">😐 Neutral</span>'
            };
            return badges[sentiment] || badges.neutral;
        }

        function analyzeSentiment(articles) {
            let positive = 0, negative = 0, neutral = 0;

            articles.forEach(article => {
                const sentiment = getSentiment(article.title + ' ' + (article.description || ''));
                if (sentiment === 'positive') positive++;
                else if (sentiment === 'negative') negative++;
                else neutral++;
            });

            document.getElementById('positiveCount').textContent = positive;
            document.getElementById('neutralCount').textContent = neutral;
            document.getElementById('negativeCount').textContent = negative;
        }

        // Load initial news
        console.log('📰 Loading initial news...');
        loadNews('supply chain');

        // Auto-refresh news setiap 15 menit untuk real-time
        setInterval(async () => {
            try {
                await loadNews(currentTopic);
                console.log('🔄 News data auto-refreshed for:', currentTopic);
            } catch (err) {
                console.error('Gagal auto-refresh news:', err);
            }
        }, 900000); // 15 menit
    </script>

</body>
</html>
