<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman form login
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('login');
    }

    // Memproses validasi akun yang dikirim dari form
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirect based on role
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard')->with('success', 'Selamat datang, Admin!');
            }
            
            return redirect('/')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah, Bro.',
        ])->onlyInput('email');
    }

    // Memproses keluar sistem (Logout)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}