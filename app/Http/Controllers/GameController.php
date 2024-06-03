<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGameRequest;
use App\Models\Game;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class GameController extends Controller
{
    public function index(): Factory|View
    {
        $games = Game::all()->sortByDesc('date');

        for ($i=0; $i<$games->count(); $i++) {
            $games[$i]->date = date_format(new \DateTime($games[$i]->date), 'd/m/Y');
            $games[$i]->players_amount = $games[$i]->players->count();
        }

        return view('game.index', ['games' => $games]);
    }

    /**
     * Display view with a creating form for a new game.
     */
    public function create(Request $request): Factory|View
    {
        return view('game.register');
    }

    /**
     * Display view with all of the players RSVP'd for the game.
     */
    public function indexConfirmedPlayers(string $gameId): Factory|View
    {
        $game = Game::findOrFail($gameId);
        $gamePlayers = $game->players->sortBy('name');

        return view(
            'game.players', 
            [
                'game' => $game,
                'gamePlayers' => $gamePlayers,
            ]
        );
    }

    public function store(StoreGameRequest $request): Redirector|RedirectResponse
    {
        Game::create([
            'label' => ucfirst($request->post('label')),
            'date' => $request->post('date'),
        ]);

        return redirect('games')->with('success', 'Partida criada com sucesso.');
    }

    /**
     * Display a listing of players available to be RSVP'd for the game.
     */
    public function indexAvailablePlayers(string $gameId): Factory|View
    {
        $game = Game::findOrFail($gameId);
        $availablePlayers =  $game->availablePlayers();

        return view(
            'player.index', 
            [
                'game' => $game,
                'players' => $availablePlayers,
            ]
        );
    }
}
