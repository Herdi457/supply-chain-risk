<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Port;
use App\Models\Article;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Dashboard admin panel index
     */
    public function index()
    {
        // Pastikan hanya admin yang bisa masuk
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Akses ditolak. Anda bukan admin.');
        }

        $users = User::all();
        $ports = Port::all();
        $articles = Article::with('author')->get();

        return view('admin.dashboard', compact('users', 'ports', 'articles'));
    }

    // ==========================================
    // CRUD USER
    // ==========================================

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:user,admin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|string|in:user,admin',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'User berhasil diperbarui.');
    }

    public function destroyUser($id)
    {
        // Mencegah admin menghapus dirinya sendiri
        if (Auth::id() == $id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus diri sendiri.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }

    // ==========================================
    // CRUD PORT
    // ==========================================

    public function storePort(Request $request)
    {
        $request->validate([
            'port_name' => 'required|string|max:255',
            'country_code' => 'required|string|max:3',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'index_number' => 'nullable|string|max:20',
        ]);

        Port::create([
            'port_name' => $request->port_name,
            'country_code' => strtoupper($request->country_code),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'index_number' => $request->index_number,
        ]);

        return redirect()->back()->with('success', 'Pelabuhan berhasil ditambahkan.');
    }

    public function updatePort(Request $request, $id)
    {
        $port = Port::findOrFail($id);

        $request->validate([
            'port_name' => 'required|string|max:255',
            'country_code' => 'required|string|max:3',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'index_number' => 'nullable|string|max:20',
        ]);

        $port->update([
            'port_name' => $request->port_name,
            'country_code' => strtoupper($request->country_code),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'index_number' => $request->index_number,
        ]);

        return redirect()->back()->with('success', 'Pelabuhan berhasil diperbarui.');
    }

    public function destroyPort($id)
    {
        $port = Port::findOrFail($id);
        $port->delete();

        return redirect()->back()->with('success', 'Pelabuhan berhasil dihapus.');
    }

    // ==========================================
    // CRUD ARTICLE
    // ==========================================

    public function storeArticle(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'risk_level' => 'required|string|in:Low Risk,Medium Risk,High Risk',
        ]);

        Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'risk_level' => $request->risk_level,
            'author_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Artikel analisis berhasil ditambahkan.');
    }

    public function updateArticle(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'risk_level' => 'required|string|in:Low Risk,Medium Risk,High Risk',
        ]);

        $article->update([
            'title' => $request->title,
            'content' => $request->content,
            'risk_level' => $request->risk_level,
        ]);

        return redirect()->back()->with('success', 'Artikel analisis berhasil diperbarui.');
    }

    public function destroyArticle($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return redirect()->back()->with('success', 'Artikel analisis berhasil dihapus.');
    }
}
