<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Player;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::all()->sortByDesc('date');

        for ($i=0; $i<$games->count(); $i++) {
            $games[$i]->date = date_format(new \DateTime($games[$i]->date), 'd/m/Y');
            $games[$i]->players_amount = $games[$i]->players->count();
        }

        return view('game.index', ['games' => $games]);
    }

    public function create(Request $request)
    {
        return view('game.register');
    }

    public function show(string $gameId)
    {
        $game = Game::findOrFail($gameId);
        $players = Player::all()->sortByDesc('ability');
        $gamePlayers = (new Game(['id' => $gameId]))->players;

        return view(
            'game.players', 
            [
                'game' => $game,
                'players' => $players,
                'gamePlayers' => $gamePlayers,
            ]
        );
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'label' => 'required|string|max:100',
            'date' => 'required|date',
        ]);

        Game::create([
            'label' => ucfirst($validatedData['label']),
            'date' => $validatedData['date'],
        ]);

        return redirect('games')->with('success', 'Partida criada com sucesso.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        //
    }
}
