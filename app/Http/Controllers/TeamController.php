<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGamePlayerRequest;
use App\Http\Requests\UpdateTeamsRequest;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Player;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;

class TeamController extends Controller
{
    /**
     * Display a listing of teams according to the game.
     */
    public function indexByGame(string $gameId): Factory|View
    {
        $game = Game::findOrFail($gameId);

        if (!GamePlayer::where('game_id', '=', $gameId)->exists()) {
            return redirect('games')
                ->withErrors([
                    'Nenhum jogador confirmado para a partida!
                    Por favor, confirme a presença de jogadores antes de gerar equipes.'
                ])
            ;
        }

        return view(
            'team.index',
            [
                'teams' => GamePlayer::teamsByGameId($gameId),
                'game' => $game,
            ]
        );
    }

    /**
     * Store a newly created game_player with the game and the RSVP'd player.
     */
    public function store(StoreGamePlayerRequest $request): Redirector|RedirectResponse
    {
        $gameId = $request->post('game_id');
        $playerId = $request->post('player_id');

        $gamePlayerExists = GamePlayer::where('game_id', $gameId)
            ->where('player_id', $playerId)
            ->exists()
        ;

        if ($gamePlayerExists) {
            return back()->withErrors('Esse jogador já confirmou presença para essa partida.');
        }

        (new GamePlayer([
            'game_id' => $gameId,
            'player_id' => $playerId,
        ]))->save();

        return redirect('available-players/'.$gameId)->with('success', 'Presença confirmada com sucesso.');
    }

    /**
     * Generate teams for a game according to the amount of players RSVP'd.
     */
    public function update(UpdateTeamsRequest $request): Redirector|RedirectResponse
    {
        $gameId = $request->post('game_id');
        $desiredPlayersCount = $request->post('players');

        $players = (new Game(['id' => $gameId]))->players->sortBy('ability');

        $validationResult = $this->validatePlayersCount($players, $desiredPlayersCount);

        if ($validationResult !== null) {
            return $validationResult;
        }

        $teams = $this->generateTeams($players, $desiredPlayersCount*2);

        $this->persistTeams($gameId, $teams);
    
        return back()->with('success', 'Equipes geradas com sucesso!');
    }

    /**
     * Generate teams by separating goalkeepers and balancing the players.
     */
    private function validatePlayersCount(
        Collection &$players,
        int $desiredPlayersCount
    ): Redirector|RedirectResponse|null {
        $playersCount = $players->count();

        if ($desiredPlayersCount <= 0) {
            return back()
                ->withErrors([
                    'A quantidade de jogadores por equipe deve ser maior que zero'
                ])
            ;
        }

        if ($playersCount/2 < $desiredPlayersCount) {
            return back()
                ->withErrors([
                    'A quantidade de jogadores por equipe não pode ser maior que '.floor($playersCount/2).'.'
                ])
            ;
        }

        return null;
    }

    /**
     * Generate teams by separating goalkeepers and balancing the players abilities.
     */
    private function generateTeams(Collection $players, int $desiredPlayersCount): array
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
            $desiredPlayersCount-=2;
        }

        switch ($playersCount) {
            case $desiredPlayersCount:
                break;
            case $desiredPlayersCount+1:
                $teams[3][] = $players->shift()->id;
                break;
            default:
                $teams[3] = $players->shift($playersCount-$desiredPlayersCount)->pluck('id')->toArray();
        }

        $playersCount = $desiredPlayersCount;

        for ($i=0; $i<$playersCount/4; $i++) {
            if ($this->twoPlayersLeft($players, $teams)) {
                break;
            };

            $teams[1][] = $players->shift()->id;
            $teams[1][] = $players->pop()->id;

            $teams[2][] = $players->shift()->id;
            $teams[2][] = $players->pop()->id;
        }

        return $teams;
    }

    private function twoPlayersLeft(
        Collection &$players,
        array &$teams,
    ): bool {
        if ($players->count() == 2) {
            $teams[2][] = $players->pop()->id;
            $teams[1][] = $players->pop()->id;

            return true;
        }

        return false;
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
    private function persistTeams(string $gameId, array $teams): void
    {
        GamePlayer::where('game_id', $gameId)->update(['team' => null]);
        foreach ($teams as $index=>$team) {
            $this->saveTeam($team, $index, $gameId);
        }
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
}
