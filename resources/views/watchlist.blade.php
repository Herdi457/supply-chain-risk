<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchlist - Supply Chain Risk</title>
    
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
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-blue-500">⭐ FAVORITE MONITORING LIST</h1>
                <p class="text-slate-400 text-xs lg:text-sm mt-1">Negara yang Anda Pantau secara Berkala</p>
            </div>
        </header>

        <!-- Add Country to Watchlist -->
        <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl mb-6">
            <h2 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                ➕ Tambah Negara ke Watchlist
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <select id="countrySelect" class="md:col-span-2 bg-slate-950 border border-slate-700 text-slate-200 px-4 py-3 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">-- Pilih Negara --</option>
                </select>
                <input type="text" id="notes" placeholder="Catatan (opsional)" 
                    class="bg-slate-950 border border-slate-700 text-slate-200 px-4 py-3 rounded-lg focus:outline-none focus:border-blue-500">
                <button onclick="addToWatchlist()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition-all">
                    Tambahkan
                </button>
            </div>
        </div>

        <!-- Watchlist Grid -->
        <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-2xl">
            <h2 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                📋 Daftar Negara yang Dipantau
            </h2>
            <div id="watchlistContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Will be filled by JavaScript -->
            </div>
        </div>

        <!-- Modal Edit Notes -->
        <div id="editModal" class="hidden fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 max-w-md w-full">
                <h3 class="text-xl font-bold text-slate-200 mb-4">Edit Catatan</h3>
                <input type="hidden" id="editId">
                <textarea id="editNotes" rows="4" 
                    class="w-full bg-slate-950 border border-slate-700 text-slate-200 px-4 py-3 rounded-lg focus:outline-none focus:border-blue-500 mb-4"
                    placeholder="Tulis catatan Anda..."></textarea>
                <div class="flex gap-3">
                    <button onclick="saveEdit()" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold transition-all">
                        Simpan
                    </button>
                    <button onclick="closeEditModal()" 
                        class="flex-1 bg-slate-700 hover:bg-slate-600 text-slate-200 px-4 py-2 rounded-lg font-bold transition-all">
                        Batal
                    </button>
                </div>
            </div>
        </div>

    </main>

    <script>
        let countriesData = [];
        let watchlistData = @json($watchlists ?? []);

        async function loadCountries() {
            try {
                console.log('Loading countries for watchlist...');
                const response = await fetch('/api/countries?limit=100');
                const data = await response.json();
                
                console.log('Countries response:', data);
                
                if (data.success) {
                    countriesData = data.data;
                    const select = document.getElementById('countrySelect');
                    
                    data.data.forEach(country => {
                        const option = document.createElement('option');
                        option.value = JSON.stringify({ code: country.code, name: country.name });
                        option.textContent = country.name;
                        select.appendChild(option);
                    });
                    
                    console.log('Countries loaded:', data.data.length);
                } else {
                    console.error('Failed to load countries:', data.message);
                }
            } catch (error) {
                console.error('Error loading countries:', error);
            }
        }

        async function loadWatchlist() {
            try {
                const response = await fetch('/api/watchlist');
                const data = await response.json();
                
                if (data.success) {
                    displayWatchlist(data.data);
                }
            } catch (error) {
                console.error('Error loading watchlist:', error);
            }
        }

        function displayWatchlist(watchlists) {
            const container = document.getElementById('watchlistContainer');
            
            if (watchlists.length === 0) {
                container.innerHTML = `
                    <div class="col-span-3 text-center py-12 text-slate-500">
                        <p class="text-2xl mb-2">📭</p>
                        <p>Belum ada negara di watchlist</p>
                        <p class="text-xs mt-2">Tambahkan negara untuk mulai memantau</p>
                    </div>
                `;
                return;
            }

            const watchlistHtml = watchlists.map(item => `
                <div class="bg-slate-950 border border-slate-800 rounded-lg p-4 hover:border-blue-600 transition-all">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="text-lg font-bold text-blue-400">${item.country_name}</h3>
                            <p class="text-xs text-slate-500">Code: ${item.country_code}</p>
                        </div>
                        <button onclick="removeFromWatchlist(${item.id})" 
                            class="text-red-400 hover:text-red-300 transition-all" title="Hapus">
                            ❌
                        </button>
                    </div>
                    
                    ${item.notes ? `
                        <div class="bg-slate-900 p-2 rounded text-xs text-slate-300 mb-3">
                            ${item.notes}
                        </div>
                    ` : ''}
                    
                    <div class="flex gap-2">
                        <button onclick="openEditModal(${item.id}, '${item.notes || ''}')" 
                            class="flex-1 bg-slate-800 hover:bg-slate-700 text-slate-200 px-3 py-1.5 rounded text-xs font-bold transition-all">
                            ✏️ Edit
                        </button>
                        <a href="/country-dashboard?code=${item.country_code}" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-xs font-bold transition-all text-center">
                            🔍 Lihat
                        </a>
                    </div>
                </div>
            `).join('');

            container.innerHTML = watchlistHtml;
        }

        async function addToWatchlist() {
            const countrySelect = document.getElementById('countrySelect');
            const notes = document.getElementById('notes').value;

            if (!countrySelect.value) {
                alert('Pilih negara terlebih dahulu');
                return;
            }

            const country = JSON.parse(countrySelect.value);

            try {
                const response = await fetch('/api/watchlist', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        country_code: country.code,
                        country_name: country.name,
                        notes: notes
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert('✅ Negara berhasil ditambahkan ke watchlist');
                    countrySelect.value = '';
                    document.getElementById('notes').value = '';
                    loadWatchlist();
                } else {
                    alert('❌ ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error menambahkan ke watchlist');
            }
        }

        async function removeFromWatchlist(id) {
            if (!confirm('Hapus negara ini dari watchlist?')) {
                return;
            }

            try {
                const response = await fetch(`/api/watchlist/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert('✅ Negara berhasil dihapus dari watchlist');
                    loadWatchlist();
                } else {
                    alert('❌ Gagal menghapus');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error menghapus dari watchlist');
            }
        }

        function openEditModal(id, notes) {
            document.getElementById('editId').value = id;
            document.getElementById('editNotes').value = notes;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        async function saveEdit() {
            const id = document.getElementById('editId').value;
            const notes = document.getElementById('editNotes').value;

            try {
                const response = await fetch(`/api/watchlist/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ notes })
                });

                const data = await response.json();

                if (data.success) {
                    alert('✅ Catatan berhasil diupdate');
                    closeEditModal();
                    loadWatchlist();
                } else {
                    alert('❌ Gagal mengupdate');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error mengupdate catatan');
            }
        }

        // Load data on page load
        loadCountries();
        loadWatchlist();
    </script>

</body>
</html>
