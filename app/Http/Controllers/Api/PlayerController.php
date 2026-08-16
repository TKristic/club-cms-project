<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index()
    {
        return Player::with('category:id,name')
            ->get(['id', 'first_name', 'last_name', 'position', 'jersey_number', 'category_id']);
    }

    public function store(Request $request)
    {
        if (! $request->user()->hasAnyRole(['admin', 'superadmin'])) {
            return response()->json(['message' => 'Nemate ovlasti.'], 403);
        }

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'position'    => 'nullable|string|max:255',
        ]);

        $data['club_id'] = Club::value('id') ?? 1;
        $player = Player::create($data);

        return response()->json($player, 201);
    }
}