<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayerRequest;
use App\Models\Player;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * Display view with a creating form for a new player.
     */
    public function create(Request $request): Factory|View
    {
        return view('player.register');
    }

    public function store(StorePlayerRequest $request): RedirectResponse
    {
        Player::create([
            'name' => $request->post('name'),
            'ability' => $request->post('ability'),
            'goalkeeper' => $request->post('goalkeeper'),
        ]);

        return back()->with('success', 'Jogador cadastrado com sucesso.');
    }
}
