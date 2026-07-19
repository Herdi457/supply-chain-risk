# Panduan Login & Role System

## Akun Default

### Admin Account
```
Email: admin@example.com
Password: password
```
**Akses:**
- Semua menu User (Peta, Negara, Cuaca, Kurs, Berita, Visualisasi, Perbandingan, Favorit)
- Admin Panel (CRUD Users, Ports, Articles)

### User Account
```
Email: user@example.com
Password: password
```
**Akses:**
- Semua menu User (Peta, Negara, Cuaca, Kurs, Berita, Visualisasi, Perbandingan, Favorit)
- TIDAK bisa akses Admin Panel

## Cara Login

1. Akses: `http://localhost:8000/login`
2. Masukkan email & password
3. Klik "MASUK KE DASHBOARD SYSTEM"

**Auto Redirect:**
- Admin → `/admin/dashboard` (Admin Panel)
- User → `/` (Map Dashboard)

## Fitur Authentication

### Protected Routes
Semua route kecuali `/login` wajib login:
- Map Dashboard
- Country Dashboard
- Currency Dashboard
- News Dashboard
- Data Visualization
- Country Comparison
- Watchlist
- Weather Monitoring

### Admin-Only Routes
Hanya user dengan `role = 'admin'` yang bisa akses:
- `/admin/dashboard` - Admin Panel CRUD

**Jika User biasa coba akses Admin Panel:**
→ Redirect ke homepage dengan pesan error

## Navbar Display

**User Login:**
```
🗺️ Peta | 🏳️ Negara | 🌤️ Cuaca | 💵 Kurs | 📰 Berita | 📊 Visualisasi | ⚖️ Perbandingan | ⭐ Favorit
[👤 User Demo] [🚪 Keluar]
```

**Admin Login:**
```
🗺️ Peta | 🏳️ Negara | 🌤️ Cuaca | 💵 Kurs | 📰 Berita | 📊 Visualisasi | ⚖️ Perbandingan | ⭐ Favorit | ⚙️ Admin
[👤 Admin Logistik (Admin)] [🚪 Keluar]
```

## Logout

Klik tombol "🚪 Keluar" di navbar → Redirect ke `/login`

## Testing

### Test Admin Access
1. Login dengan `admin@example.com`
2. Cek navbar → ada menu "⚙️ Admin"
3. Klik "⚙️ Admin" → bisa akses Admin Panel
4. Semua menu User juga bisa diakses

### Test User Access
1. Login dengan `user@example.com`
2. Cek navbar → TIDAK ada menu "⚙️ Admin"
3. Coba akses manual `http://localhost:8000/admin/dashboard` → Redirect + error message
4. Semua menu User bisa diakses normal

## Middleware

**CheckAdmin Middleware:**
- File: `app/Http/Middleware/CheckAdmin.php`
- Check: `auth()->user()->role === 'admin'`
- Registered di: `bootstrap/app.php` sebagai `'admin'`

**Auth Middleware:**
- Semua route (kecuali login) ada di dalam `Route::middleware(['auth'])`

## Database

**Users Table:**
- `id` - Primary key
- `name` - Nama user
- `email` - Email (unique)
- `password` - Hashed password
- `role` - enum('user', 'admin')
- `created_at`, `updated_at`

## Troubleshooting

**"Akses ditolak. Halaman ini khusus admin"**
→ User biasa coba akses admin route. Normal behavior.

**Tidak bisa login**
→ Cek email/password, atau jalankan `php artisan db:seed` ulang

**Logout tidak bekerja**
→ Clear browser cache, atau cek CSRF token

**Menu admin tidak muncul meskipun login sebagai admin**
→ Check: `php artisan tinker` → `User::where('email', 'admin@example.com')->first()->role` harus return `'admin'`

## File Changes

- `routes/web.php` - Aktivasi middleware auth & admin
- `app/Http/Controllers/AuthController.php` - Role-based redirect
- `app/Http/Middleware/CheckAdmin.php` - Admin check middleware
- `bootstrap/app.php` - Register middleware alias
- `resources/views/partials/navbar.blade.php` - Conditional admin menu
- `database/seeders/DatabaseSeeder.php` - Seed user & admin accounts
