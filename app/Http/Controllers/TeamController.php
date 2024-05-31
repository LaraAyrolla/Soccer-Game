<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Player;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'game_id' => 'required|uuid|exists:games,id',
            'player_id' => 'required|uuid|exists:players,id',
        ]);

        $gamePlayerExists = GamePlayer::where('game_id', $validatedData['game_id'])
            ->where('player_id', $validatedData['player_id'])
            ->exists()
        ;

        if ($gamePlayerExists) {
            throw new Exception('This player is already confirmed for this game');
        }

        $gamePlayer = new GamePlayer([
            'game_id' => $validatedData['game_id'],
            'player_id' => $validatedData['player_id'],
        ]);
        
        $gamePlayer->save();

        return $gamePlayer;
    }

    /**
     * Display the specified resource.
     */
    public function show(GamePlayer $gamePlayer)
    {
        //
    }

    /**
     * Generate teams for a game according to the amount of players per team.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'game_id' => 'required|uuid|exists:games,id',
            'players' => 'required|integer|min:1'
        ]);

        return $this->generateTeams($validatedData['game_id']);
    }

    /**
     * Generate teams by separating goalkeepers and balancing the players.
     */
    private function generateTeams($gameId)
    {
        $players = (new Game(['id' => $gameId]))->players->sortBy('ability');

        //TODO: add this validation
        //validate that the players number is not inferior to double the team limit
        ////if there's MORE, forbid it
        $playersCount = $players->count();

        if ($playersCount%2 != 0) {
            throw new Exception("The amount of players confirmed for the game must be even.");
        }

        $teams = [];

        $goalkeepers = $players->where('goalkeeper', '=', 1);

        if ($goalkeepers->count() >= 2) {
            $id = $this->extractGoalkeeper($goalkeepers, $players);
            $teams[1][] = $id;

            $id = $this->extractGoalkeeper($goalkeepers, $players);
            $teams[2][] = $id;

            $playersCount-=2;
        }

        for ($i=0; $i<$playersCount; $i+=2) {
            $id = $players->pop()->id;
            $teams[1][] = $id;
            $id = $players->pop()->id;
            $teams[2][] = $id;
        }

        GamePlayer::whereIn('player_id', $teams[1])
            ->where('game_id', $gameId)
            ->update([
                'team' => 1
            ])
        ;

        GamePlayer::whereIn('player_id', $teams[2])
            ->where('game_id', $gameId)
            ->update([
                'team' => 2
            ])
        ;

        return GamePlayer::where('game_id', $gameId)->get();
    }

    private function extractGoalkeeper(Collection &$goalkeepers, Collection &$players): string
    {
        $id = $goalkeepers->pop()->id;
    
        $players = $players->reject(
            function (Player $player) use ($id) {
                return $player->id == $id;
            }
        );

        return $id;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GamePlayer $gamePlayer)
    {
        //
    }
}
