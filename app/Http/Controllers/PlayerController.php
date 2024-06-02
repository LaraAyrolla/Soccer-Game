<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class PlayerController extends Controller
{
    /**
     * Display view with a creating form for a new player.
     */
    public function create(Request $request)
    {
        return view('player.register');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:100|unique:players',
            'ability' => 'required|integer|min:1|max:5',
            'goalkeeper' => 'required|boolean',
        ]);

        $player = Player::create([
            'name' => ucwords($validatedData['name']), //TODO: add passes validation
            'ability' => $validatedData['ability'],
            'goalkeeper' => $validatedData['goalkeeper'],
        ]);

        return $player;
    }
}
