<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Watchlist;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    public function index()
    {
        $watchlists = Watchlist::where('user_id', Auth::id())->get();
        return view('watchlist', compact('watchlists'));
    }

    public function store(Request $request)
    {
        \Log::info('📥 Watchlist store request', [
            'user_id' => Auth::id(),
            'user_role' => Auth::user()->role ?? 'unknown',
            'data' => $request->all()
        ]);

        $request->validate([
            'country_code' => 'required|string|max:3',
            'country_name' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        // Check if already exists
        $exists = Watchlist::where('user_id', Auth::id())
            ->where('country_code', $request->country_code)
            ->exists();

        if ($exists) {
            \Log::warning('⚠️ Country already in watchlist', [
                'user_id' => Auth::id(),
                'country_code' => $request->country_code
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Country already in watchlist'
            ], 400);
        }

        $watchlist = Watchlist::create([
            'user_id' => Auth::id(),
            'country_code' => $request->country_code,
            'country_name' => $request->country_name,
            'notes' => $request->notes
        ]);

        \Log::info('✅ Watchlist created', ['id' => $watchlist->id]);

        return response()->json([
            'success' => true,
            'message' => 'Country added to watchlist',
            'data' => $watchlist
        ]);
    }

    public function update(Request $request, $id)
    {
        $watchlist = Watchlist::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'notes' => 'nullable|string'
        ]);

        $watchlist->update([
            'notes' => $request->notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Watchlist updated',
            'data' => $watchlist
        ]);
    }

    public function destroy($id)
    {
        $watchlist = Watchlist::where('user_id', Auth::id())->findOrFail($id);
        $watchlist->delete();

        return response()->json([
            'success' => true,
            'message' => 'Country removed from watchlist'
        ]);
    }

    public function getAll()
    {
        $watchlists = Watchlist::where('user_id', Auth::id())->get();
        
        return response()->json([
            'success' => true,
            'data' => $watchlists
        ]);
    }
}
