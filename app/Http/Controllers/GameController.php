<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $games = Game::all()->sortBy('date');

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

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'label' => 'required|string|max:100',
            'date' => 'required|date',
        ]);

        $game = Game::create([
            'label' => ucfirst($validatedData['label']),
            'date' => $validatedData['date'],
        ]);

        return redirect('games')->with('success', 'Partida criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        // $playersCount = (new Game(['id' => $gameId]))->players->count();
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
