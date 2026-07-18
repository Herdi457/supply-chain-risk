<nav class="bg-slate-900 border-b border-slate-800 sticky top-0 z-50 px-4 py-3 lg:px-6 shadow-md">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Logo and Branding -->
        <div class="flex items-center gap-3 w-full md:w-auto justify-between">
            <a href="/dashboard" class="flex items-center gap-2.5 no-underline">
                <span class="text-2xl">🌍</span>
                <div>
                    <h2 class="font-black text-sm tracking-wider text-blue-500 leading-none">GSCMS PLATFORM</h2>
                    <span class="text-[9px] text-slate-400 uppercase font-bold tracking-tight">Risk Logistics Engine</span>
                </div>
            </a>
            
            <!-- Mobile Menu Toggle Button (optional, but let's keep it responsive) -->
            <button onclick="toggleMobileMenu()" class="md:hidden text-slate-400 hover:text-slate-200">
                <span class="text-xl">☰</span>
            </button>
        </div>

        <!-- Navigation Links -->
        <div id="nav-links" class="hidden md:flex flex-wrap items-center gap-1.5 text-xs lg:text-sm font-bold w-full md:w-auto mt-2 md:mt-0">
            <a href="/dashboard" class="px-3 py-2 rounded-lg transition-all {{ request()->is('dashboard') || request()->is('/') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                🗺️ Peta
            </a>
            <a href="/country-dashboard" class="px-3 py-2 rounded-lg transition-all {{ request()->is('country-dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                🏳️ Negara
            </a>
            <a href="/weather-monitoring" class="px-3 py-2 rounded-lg transition-all {{ request()->is('weather-monitoring') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                🌤️ Cuaca
            </a>
            <a href="/currency-dashboard" class="px-3 py-2 rounded-lg transition-all {{ request()->is('currency-dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                💵 Kurs
            </a>
            <a href="/news-dashboard" class="px-3 py-2 rounded-lg transition-all {{ request()->is('news-dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                📰 Berita
            </a>
            <a href="/data-visualization" class="px-3 py-2 rounded-lg transition-all {{ request()->is('data-visualization') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                📊 Visualisasi
            </a>
            <a href="/country-comparison" class="px-3 py-2 rounded-lg transition-all {{ request()->is('country-comparison') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                ⚖️ Perbandingan
            </a>
            <a href="/watchlist" class="px-3 py-2 rounded-lg transition-all {{ request()->is('watchlist') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                ⭐ Favorit
            </a>

            @if(auth()->user() && auth()->user()->role === 'admin')
                <a href="/admin/dashboard" class="px-3 py-2 rounded-lg transition-all {{ request()->is('admin/*') ? 'bg-purple-600 text-white shadow-md' : 'text-purple-400 hover:text-purple-200 hover:bg-purple-850/20 border border-purple-950/20' }}">
                    ⚙️ Admin
                </a>
            @endif
        </div>

        <!-- User Info & Logout -->
        <div class="hidden md:flex items-center gap-3">
            @if(auth()->check())
            <span class="text-xs text-slate-400 bg-slate-950 px-2.5 py-1 rounded border border-slate-800 font-mono">
                👤 {{ auth()->user()->name }}
            </span>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 text-red-400 px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer">
                    🚪 Keluar
                </button>
            </form>
            @else
            <span class="text-xs text-slate-400 bg-slate-950 px-2.5 py-1 rounded border border-slate-800 font-mono">
                👤 Guest
            </span>
            @endif
        </div>
    </div>
</nav>

<script>
    function toggleMobileMenu() {
        const navLinks = document.getElementById('nav-links');
        if (navLinks.classList.contains('hidden')) {
            navLinks.classList.remove('hidden');
            navLinks.classList.add('flex', 'flex-col', 'w-full', 'border-t', 'border-slate-800', 'pt-3', 'mt-3');
        } else {
            navLinks.classList.add('hidden');
            navLinks.classList.remove('flex', 'flex-col', 'w-full', 'border-t', 'border-slate-800', 'pt-3', 'mt-3');
        }
    }
</script>
