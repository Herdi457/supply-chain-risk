<aside id="sidebar" class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col fixed inset-y-0 left-0 z-20 sidebar-transition">
    <div class="p-5 border-b border-slate-800 flex items-center justify-between gap-2 overflow-hidden">
        <div class="flex items-center gap-3 sidebar-text whitespace-nowrap">
            <span class="text-2xl">🌍</span>
            <div>
                <h2 class="font-black text-sm tracking-wider text-blue-500">GSCMS PLATFORM</h2>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-tight">Risk Logistics Engine</p>
            </div>
        </div>
        <button onclick="toggleSidebar()" class="bg-slate-800 hover:bg-slate-700 text-slate-300 p-1.5 rounded-lg border border-slate-700 cursor-pointer transition-colors" title="Buka/Tutup Sidebar">
            <span id="toggle-icon">◀</span>
        </button>
    </div>

    <nav class="flex-1 p-4 space-y-6 overflow-y-auto custom-scrollbar overflow-x-hidden">
        <div>
            <p class="px-3 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2 sidebar-text">Dashboard</p>
            <a href="/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold {{ request()->is('dashboard') || request()->is('/') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }} transition-all">
                <span class="text-base flex-shrink-0">🗺️</span> <span class="sidebar-text whitespace-nowrap">Dashboard Peta</span>
            </a>
        </div>

        <div>
            <p class="px-3 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2 sidebar-text">Monitoring</p>
            <div class="space-y-1">
                <a href="/country-dashboard" class="flex items-center gap-3 px-3 py-2 text-sm {{ request()->is('country-dashboard') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }} rounded-lg transition-all">
                    <span class="text-base flex-shrink-0">🏳️</span> <span class="sidebar-text whitespace-nowrap">Data Negara</span>
                </a>
                <a href="/weather-monitoring" class="flex items-center gap-3 px-3 py-2 text-sm {{ request()->is('weather-monitoring') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }} rounded-lg transition-all">
                    <span class="text-base flex-shrink-0">🌤️</span> <span class="sidebar-text whitespace-nowrap">Monitoring Cuaca</span>
                </a>
                <a href="/currency-dashboard" class="flex items-center gap-3 px-3 py-2 text-sm {{ request()->is('currency-dashboard') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }} rounded-lg transition-all">
                    <span class="text-base flex-shrink-0">💵</span> <span class="sidebar-text whitespace-nowrap">Nilai Tukar Mata Uang</span>
                </a>
                <a href="/news-dashboard" class="flex items-center gap-3 px-3 py-2 text-sm {{ request()->is('news-dashboard') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }} rounded-lg transition-all">
                    <span class="text-base flex-shrink-0">📰</span> <span class="sidebar-text whitespace-nowrap">Berita Global</span>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2 sidebar-text">Analytics</p>
            <div class="space-y-1">
                <a href="/data-visualization" class="flex items-center gap-3 px-3 py-2 text-sm {{ request()->is('data-visualization') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }} rounded-lg transition-all">
                    <span class="text-base flex-shrink-0">📊</span> <span class="sidebar-text whitespace-nowrap">Visualisasi Data</span>
                </a>
                <a href="/country-comparison" class="flex items-center gap-3 px-3 py-2 text-sm {{ request()->is('country-comparison') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }} rounded-lg transition-all">
                    <span class="text-base flex-shrink-0">⚖️</span> <span class="sidebar-text whitespace-nowrap">Perbandingan Negara</span>
                </a>
                <a href="/watchlist" class="flex items-center gap-3 px-3 py-2 text-sm {{ request()->is('watchlist') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }} rounded-lg transition-all">
                    <span class="text-base flex-shrink-0">⭐</span> <span class="sidebar-text whitespace-nowrap">Favorit Saya</span>
                </a>
            </div>
        </div>

        @if(auth()->user() && auth()->user()->role === 'admin')
        <div>
            <p class="px-3 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2 sidebar-text">Admin</p>
            <div class="space-y-1">
                <a href="/admin/dashboard" class="flex items-center gap-3 px-3 py-2 text-sm {{ request()->is('admin/*') ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }} rounded-lg transition-all">
                    <span class="text-base flex-shrink-0">⚙️</span> <span class="sidebar-text whitespace-nowrap">Admin Panel</span>
                </a>
            </div>
        </div>
        @endif
    </nav>

    <div class="p-4 border-t border-slate-800 bg-slate-950/40 overflow-hidden">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 text-red-400 px-3 py-2 rounded-lg text-xs font-bold transition-all cursor-pointer text-center block whitespace-nowrap">
                <span class="sidebar-text">🚪 Keluar dari Aplikasi</span>
            </button>
        </form>
    </div>
</aside>

<style>
    .sidebar-transition {
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #0f172a; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
</style>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const toggleIcon = document.getElementById('toggle-icon');
        const textElements = document.querySelectorAll('.sidebar-text');

        if (sidebar.classList.contains('w-64')) {
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-16');
            toggleIcon.innerText = '▶';
            textElements.forEach(el => el.classList.add('hidden'));
        } else {
            sidebar.classList.remove('w-16');
            sidebar.classList.add('w-64');
            toggleIcon.innerText = '◀';
            textElements.forEach(el => el.classList.remove('hidden'));
        }
    }
</script>
