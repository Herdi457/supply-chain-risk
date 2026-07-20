<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Logistik - Register User</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-950 text-slate-100 flex items-center justify-center min-h-screen font-sans antialiased">

    <div class="w-full max-w-md p-8 space-y-6 bg-slate-900 rounded-xl border border-slate-800 shadow-2xl">
        
        <div class="text-center space-y-2">
            <h1 class="text-2xl font-black tracking-wider text-blue-500 uppercase">Create Account</h1>
            <p class="text-slate-400 text-xs">Daftar akun baru untuk menggunakan sistem Supply Chain Risk</p>
        </div>

        @if ($errors->any())
            <div class="p-3 bg-red-500/10 border border-red-500/30 text-red-400 text-xs rounded-lg">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="p-3 bg-green-500/10 border border-green-500/30 text-green-400 text-xs rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form action="/register" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="John Doe"
                       class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-lg focus:outline-none focus:border-blue-500 text-sm transition-colors text-slate-200">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="user@example.com"
                       class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-lg focus:outline-none focus:border-blue-500 text-sm transition-colors text-slate-200">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Kata Sandi (Password)</label>
                <input type="password" name="password" required placeholder="Min. 8 karakter"
                       class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-lg focus:outline-none focus:border-blue-500 text-sm transition-colors text-slate-200">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required placeholder="Ketik ulang password"
                       class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-lg focus:outline-none focus:border-blue-500 text-sm transition-colors text-slate-200">
            </div>

            <button type="submit" 
                    class="w-full py-3 mt-6 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-lg shadow-lg shadow-blue-500/20 transition-all duration-200 cursor-pointer">
                DAFTAR AKUN BARU →
            </button>
        </form>

        <div class="text-center pt-4">
            <p class="text-slate-400 text-xs">
                Sudah punya akun? 
                <a href="/login" class="text-blue-500 hover:text-blue-400 font-semibold transition-colors">Login di sini</a>
            </p>
        </div>

        <div class="text-center pt-2 border-t border-slate-800/60">
            <span class="text-[10px] text-slate-500 uppercase tracking-widest">Supply Chain Risk Engine v1.0</span>
        </div>
    </div>

</body>
</html>
