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

        $gameId = $validatedData['game_id'];

        $players = (new Game(['id' => $gameId]))->players->sortBy('ability');

        $this->validatePlayersCount($players, $validatedData['players']);

        $teams = $this->generateTeams($players);

        return $this->persistTeams($gameId, $teams);
    }

    /**
     * Generate teams by separating goalkeepers and balancing the players.
     */
    private function validatePlayersCount(Collection &$players, int $teamCount): void
    {
        $playersCount = $players->count();
        $desiredPlayersCount = $teamCount;

        if ($playersCount <= 0) {
            throw new Exception("The amount of players confirmed for the game must be greater than zero.");
        }

        if ($playersCount%2 != 0) {
            throw new Exception("The amount of players confirmed for the game must be even.");
        }

        if ($playersCount/2 < $desiredPlayersCount) {
            throw new Exception(
                "The amount of players confirmed for the game cannot be less than "
                . $desiredPlayersCount
            );
        }

        if ($playersCount/2 > $desiredPlayersCount) {
            throw new Exception(
                "The amount of players confirmed for the game cannot be more than "
                . $desiredPlayersCount
            );
        }
    }

    /**
     * Generate teams by separating goalkeepers and balancing the players abilities.
     */
    private function generateTeams(Collection &$players): array
    {
        $teams = [];

        $goalkeepers = $players->where('goalkeeper', '=', 1);
        $playersCount = $players->count();

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

        return $teams;
    }

    /**
     * Extract goalkeeper from original collections after being added into a team.
     */
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
     * Persist teams in the database and returning game_players records.
     */
    private function persistTeams(string $gameId, array $teams): GamePlayer|Collection
    {
        $this->saveTeam($teams[1], 1, $gameId);
        $this->saveTeam($teams[2], 2, $gameId);

        return GamePlayer::where('game_id', $gameId)->get();
    }

    /**
     * Persist teams in the database by updating game_players rows.
     */
    private function saveTeam(array $team, int $index, string $gameId): void
    {
        GamePlayer::whereIn('player_id', $team)
            ->where('game_id', $gameId)
            ->update([
                'team' => $index
            ])
        ;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GamePlayer $gamePlayer)
    {
        //
    }
}
