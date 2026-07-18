<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - GSCMS Platform</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #0f172a; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased min-h-screen">

    @include('partials.navbar')

    <main id="main-content" class="max-w-7xl mx-auto p-6 lg:p-8">
        <!-- Toast Notification -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-lg text-sm font-bold flex items-center justify-between">
                <span>✅ {{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200">✕</button>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-lg text-sm font-bold flex items-center justify-between">
                <span>⚠️ {{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-200">✕</button>
            </div>
        @endif

        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b border-slate-800 pb-4 gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-purple-500 flex items-center gap-2">
                    ⚙️ PANEL KONTROL ADMIN
                </h1>
                <p class="text-slate-400 text-xs lg:text-sm mt-1">
                    Kelola data pengguna, dataset pelabuhan logistik, dan artikel analisis risiko sistem
                </p>
            </div>
            <div class="bg-slate-900 px-4 py-2 rounded-lg border border-slate-800 text-xs font-medium shadow-md flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-purple-500 rounded-full"></span>
                <span class="text-slate-300 font-bold">Admin: {{ auth()->user()->name }}</span>
            </div>
        </header>

        <!-- Statistik Utama -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-slate-900 p-5 rounded-xl border border-slate-800 flex items-center justify-between shadow-lg">
                <div>
                    <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Total Pengguna</p>
                    <h3 class="text-3xl font-black text-white mt-1">{{ $users->count() }}</h3>
                </div>
                <span class="text-3xl p-3 bg-blue-500/10 text-blue-400 rounded-xl">👥</span>
            </div>
            <div class="bg-slate-900 p-5 rounded-xl border border-slate-800 flex items-center justify-between shadow-lg">
                <div>
                    <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Dataset Pelabuhan</p>
                    <h3 class="text-3xl font-black text-white mt-1">{{ $ports->count() }}</h3>
                </div>
                <span class="text-3xl p-3 bg-emerald-500/10 text-emerald-400 rounded-xl">🚢</span>
            </div>
            <div class="bg-slate-900 p-5 rounded-xl border border-slate-800 flex items-center justify-between shadow-lg">
                <div>
                    <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Artikel Analisis</p>
                    <h3 class="text-3xl font-black text-white mt-1">{{ $articles->count() }}</h3>
                </div>
                <span class="text-3xl p-3 bg-purple-500/10 text-purple-400 rounded-xl">📰</span>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex border-b border-slate-800 mb-6 gap-2">
            <button onclick="switchTab('tab-users')" id="btn-tab-users" class="tab-btn px-4 py-2.5 font-bold text-sm border-b-2 border-purple-500 text-purple-400 transition-all cursor-pointer">
                👥 Kelola User
            </button>
            <button onclick="switchTab('tab-ports')" id="btn-tab-ports" class="tab-btn px-4 py-2.5 font-bold text-sm border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-all cursor-pointer">
                🚢 Kelola Pelabuhan
            </button>
            <button onclick="switchTab('tab-articles')" id="btn-tab-articles" class="tab-btn px-4 py-2.5 font-bold text-sm border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-all cursor-pointer">
                📰 Kelola Artikel
            </button>
        </div>

        <!-- ==================================================================== -->
        <!-- TAB 1: KELOLA USER -->
        <!-- ==================================================================== -->
        <div id="tab-users" class="tab-content block">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-slate-200">Daftar Pengguna Sistem</h3>
                <button onclick="openModal('modal-create-user')" class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs px-3.5 py-2 rounded-lg shadow-lg cursor-pointer transition-all">
                    ➕ Tambah User
                </button>
            </div>
            
            <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-950 text-slate-400 border-b border-slate-800 text-[11px] uppercase tracking-wider font-extrabold">
                            <tr>
                                <th class="p-4">Nama</th>
                                <th class="p-4">Email</th>
                                <th class="p-4">Role</th>
                                <th class="p-4">Dibuat Pada</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 font-medium">
                            @foreach($users as $user)
                                <tr class="hover:bg-slate-800/20 transition-all">
                                    <td class="p-4 font-bold text-white">{{ $user->name }}</td>
                                    <td class="p-4 text-slate-300">{{ $user->email }}</td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $user->role === 'admin' ? 'bg-purple-500/10 text-purple-400 border border-purple-500/25' : 'bg-slate-500/10 text-slate-400 border border-slate-800' }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-slate-400 text-xs">{{ $user->created_at->format('d M Y H:i') }}</td>
                                    <td class="p-4 text-right space-x-1">
                                        <button onclick="editUser({{ $user }})" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-2.5 py-1.5 rounded text-xs font-bold transition-all cursor-pointer">✏️ Edit</button>
                                        @if(auth()->id() !== $user->id)
                                            <form action="/admin/users/{{ $user->id }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus user ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 px-2.5 py-1.5 rounded text-xs font-bold transition-all cursor-pointer">🗑️ Hapus</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ==================================================================== -->
        <!-- TAB 2: KELOLA PORT -->
        <!-- ==================================================================== -->
        <div id="tab-ports" class="tab-content hidden">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-slate-200">Daftar Pelabuhan Logistik</h3>
                <button onclick="openModal('modal-create-port')" class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs px-3.5 py-2 rounded-lg shadow-lg cursor-pointer transition-all">
                    ➕ Tambah Pelabuhan
                </button>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-2xl">
                <div class="overflow-x-auto max-h-[500px] custom-scrollbar">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-950 text-slate-400 border-b border-slate-800 text-[11px] uppercase tracking-wider font-extrabold sticky top-0 z-10">
                            <tr>
                                <th class="p-4">Nama Pelabuhan</th>
                                <th class="p-4">Kode Negara</th>
                                <th class="p-4">Koordinat (Lat, Lng)</th>
                                <th class="p-4">Index WPI</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 font-medium">
                            @foreach($ports as $port)
                                <tr class="hover:bg-slate-800/20 transition-all">
                                    <td class="p-4 font-bold text-white">🚢 {{ $port->port_name }}</td>
                                    <td class="p-4 font-black text-blue-400 uppercase">{{ $port->country_code }}</td>
                                    <td class="p-4 text-slate-300 font-mono text-xs">{{ $port->latitude }}°, {{ $port->longitude }}°</td>
                                    <td class="p-4 text-slate-400 text-xs font-mono">{{ $port->index_number ?? '-' }}</td>
                                    <td class="p-4 text-right space-x-1 whitespace-nowrap">
                                        <button onclick="editPort({{ $port }})" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-2.5 py-1.5 rounded text-xs font-bold transition-all cursor-pointer">✏️ Edit</button>
                                        <form action="/admin/ports/{{ $port->id }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus pelabuhan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 px-2.5 py-1.5 rounded text-xs font-bold transition-all cursor-pointer">🗑️ Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ==================================================================== -->
        <!-- TAB 3: KELOLA ARTIKEL -->
        <!-- ==================================================================== -->
        <div id="tab-articles" class="tab-content hidden">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-slate-200">Daftar Artikel Analisis Risiko</h3>
                <button onclick="openModal('modal-create-article')" class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs px-3.5 py-2 rounded-lg shadow-lg cursor-pointer transition-all">
                    ➕ Buat Artikel
                </button>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-950 text-slate-400 border-b border-slate-800 text-[11px] uppercase tracking-wider font-extrabold">
                            <tr>
                                <th class="p-4">Judul</th>
                                <th class="p-4">Tingkat Risiko</th>
                                <th class="p-4">Penulis</th>
                                <th class="p-4">Terakhir Diupdate</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 font-medium">
                            @forelse($articles as $article)
                                <tr class="hover:bg-slate-800/20 transition-all">
                                    <td class="p-4 font-bold text-white">
                                        <p>{{ $article->title }}</p>
                                        <p class="text-slate-400 text-xs font-medium mt-1 truncate max-w-sm">{{ Str::limit($article->content, 60) }}</p>
                                    </td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-extrabold uppercase 
                                            {{ $article->risk_level == 'High Risk' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : ($article->risk_level == 'Medium Risk' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20') }}">
                                            {{ $article->risk_level }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-slate-300 text-xs font-bold">{{ $article->author->name ?? 'Sistem' }}</td>
                                    <td class="p-4 text-slate-400 text-xs">{{ $article->updated_at->format('d M Y H:i') }}</td>
                                    <td class="p-4 text-right space-x-1 whitespace-nowrap">
                                        <button onclick="editArticle({{ $article }})" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-2.5 py-1.5 rounded text-xs font-bold transition-all cursor-pointer">✏️ Edit</button>
                                        <form action="/admin/articles/{{ $article->id }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus artikel ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 px-2.5 py-1.5 rounded text-xs font-bold transition-all cursor-pointer">🗑️ Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-500 font-bold">Belum ada artikel analisis yang dibuat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ==================================================================== -->
        <!-- MODALS (POPUP FORMS) -->
        <!-- ==================================================================== -->

        <!-- 1. Modal Create User -->
        <div id="modal-create-user" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden">
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-xl w-full max-w-md shadow-2xl">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-black text-slate-200">Tambah Pengguna Baru</h3>
                    <button onclick="closeModal('modal-create-user')" class="text-slate-400 hover:text-slate-200 text-lg">✕</button>
                </div>
                <form action="/admin/users" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Nama</label>
                        <input type="text" name="name" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Password</label>
                        <input type="password" name="password" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Role</label>
                        <select name="role" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200">
                            <option value="user">User biasa</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 rounded-lg shadow-md cursor-pointer transition-all">Simpan User</button>
                </form>
            </div>
        </div>

        <!-- 2. Modal Edit User -->
        <div id="modal-edit-user" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden">
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-xl w-full max-w-md shadow-2xl">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-black text-slate-200">Perbarui Pengguna</h3>
                    <button onclick="closeModal('modal-edit-user')" class="text-slate-400 hover:text-slate-200 text-lg">✕</button>
                </div>
                <form id="form-edit-user" action="" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Nama</label>
                        <input type="text" id="edit-user-name" name="name" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Email</label>
                        <input type="email" id="edit-user-email" name="email" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Password (Kosongkan jika tidak diganti)</label>
                        <input type="password" name="password" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200" placeholder="Minimal 6 karakter">
                    </div>
                    <div>
                        <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Role</label>
                        <select id="edit-user-role" name="role" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200">
                            <option value="user">User biasa</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 rounded-lg shadow-md cursor-pointer transition-all">Simpan Perubahan</button>
                </form>
            </div>
        </div>

        <!-- 3. Modal Create Port -->
        <div id="modal-create-port" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden">
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-xl w-full max-w-md shadow-2xl">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-black text-slate-200">Tambah Pelabuhan Baru</h3>
                    <button onclick="closeModal('modal-create-port')" class="text-slate-400 hover:text-slate-200 text-lg">✕</button>
                </div>
                <form action="/admin/ports" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Nama Pelabuhan</label>
                        <input type="text" name="port_name" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Kode Negara (2-3 huruf)</label>
                        <input type="text" name="country_code" required placeholder="Contoh: ID, CN, US" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200 uppercase">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Latitude</label>
                            <input type="number" name="latitude" step="any" required placeholder="S -90 s/d 90" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200">
                        </div>
                        <div>
                            <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Longitude</label>
                            <input type="number" name="longitude" step="any" required placeholder="B -180 s/d 180" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Index WPI (Nomor Port)</label>
                        <input type="text" name="index_number" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200">
                    </div>
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 rounded-lg shadow-md cursor-pointer transition-all">Simpan Pelabuhan</button>
                </form>
            </div>
        </div>

        <!-- 4. Modal Edit Port -->
        <div id="modal-edit-port" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden">
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-xl w-full max-w-md shadow-2xl">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-black text-slate-200">Perbarui Pelabuhan</h3>
                    <button onclick="closeModal('modal-edit-port')" class="text-slate-400 hover:text-slate-200 text-lg">✕</button>
                </div>
                <form id="form-edit-port" action="" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Nama Pelabuhan</label>
                        <input type="text" id="edit-port-name" name="port_name" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Kode Negara</label>
                        <input type="text" id="edit-port-code" name="country_code" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200 uppercase">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Latitude</label>
                            <input type="number" id="edit-port-lat" name="latitude" step="any" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200">
                        </div>
                        <div>
                            <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Longitude</label>
                            <input type="number" id="edit-port-lng" name="longitude" step="any" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Index WPI</label>
                        <input type="text" id="edit-port-index" name="index_number" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200">
                    </div>
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 rounded-lg shadow-md cursor-pointer transition-all">Simpan Perubahan</button>
                </form>
            </div>
        </div>

        <!-- 5. Modal Create Article -->
        <div id="modal-create-article" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden">
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-xl w-full max-w-xl shadow-2xl">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-black text-slate-200">Buat Artikel Analisis Risiko</h3>
                    <button onclick="closeModal('modal-create-article')" class="text-slate-400 hover:text-slate-200 text-lg">✕</button>
                </div>
                <form action="/admin/articles" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Judul Artikel</label>
                        <input type="text" name="title" required placeholder="Krisis Rantai Pasok Global Terkini..." class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Tingkat Risiko Terkait</label>
                        <select name="risk_level" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200">
                            <option value="Low Risk">Low Risk</option>
                            <option value="Medium Risk">Medium Risk</option>
                            <option value="High Risk">High Risk</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Konten / Isi Analisis</label>
                        <textarea name="content" required rows="6" placeholder="Tuliskan analisis detail risiko di sini..." class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200 custom-scrollbar"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 rounded-lg shadow-md cursor-pointer transition-all">Posting Artikel</button>
                </form>
            </div>
        </div>

        <!-- 6. Modal Edit Article -->
        <div id="modal-edit-article" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden">
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-xl w-full max-w-xl shadow-2xl">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-black text-slate-200">Perbarui Artikel Analisis</h3>
                    <button onclick="closeModal('modal-edit-article')" class="text-slate-400 hover:text-slate-200 text-lg">✕</button>
                </div>
                <form id="form-edit-article" action="" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Judul Artikel</label>
                        <input type="text" id="edit-article-title" name="title" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Tingkat Risiko</label>
                        <select id="edit-article-risk" name="risk_level" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200">
                            <option value="Low Risk">Low Risk</option>
                            <option value="Medium Risk">Medium Risk</option>
                            <option value="High Risk">High Risk</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs uppercase font-extrabold text-slate-400 mb-1">Konten / Isi Analisis</label>
                        <textarea id="edit-article-content" name="content" required rows="6" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-purple-500 text-slate-200 custom-scrollbar"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 rounded-lg shadow-md cursor-pointer transition-all">Simpan Perubahan</button>
                </form>
            </div>
        </div>

    </main>

    <script>
        // Tab Switcher
        function switchTab(tabId) {
            // Hide all contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.replace('block', 'hidden'));

            // Show active content
            document.getElementById(tabId).classList.replace('hidden', 'block');

            // Deactivate all tab buttons
            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(btn => {
                btn.classList.replace('border-purple-500', 'border-transparent');
                btn.classList.replace('text-purple-400', 'text-slate-400');
            });

            // Activate active tab button
            const activeBtn = document.getElementById(`btn-${tabId}`);
            activeBtn.classList.replace('border-transparent', 'border-purple-500');
            activeBtn.classList.replace('text-slate-400', 'text-purple-400');
        }

        // Modal Controls
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
        }

        // Edit fill-in helpers
        function editUser(user) {
            document.getElementById('form-edit-user').action = `/admin/users/${user.id}`;
            document.getElementById('edit-user-name').value = user.name;
            document.getElementById('edit-user-email').value = user.email;
            document.getElementById('edit-user-role').value = user.role;
            openModal('modal-edit-user');
        }

        function editPort(port) {
            document.getElementById('form-edit-port').action = `/admin/ports/${port.id}`;
            document.getElementById('edit-port-name').value = port.port_name;
            document.getElementById('edit-port-code').value = port.country_code;
            document.getElementById('edit-port-lat').value = port.latitude;
            document.getElementById('edit-port-lng').value = port.longitude;
            document.getElementById('edit-port-index').value = port.index_number || '';
            openModal('modal-edit-port');
        }

        function editArticle(article) {
            document.getElementById('form-edit-article').action = `/admin/articles/${article.id}`;
            document.getElementById('edit-article-title').value = article.title;
            document.getElementById('edit-article-risk').value = article.risk_level;
            document.getElementById('edit-article-content').value = article.content;
            openModal('modal-edit-article');
        }
    </script>
</body>
</html>
