<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexAvailablePlayersViewWithNoPlayers()
    {
        $game = Game::factory()->create();

        $response = $this->get('/available-players/'.$game->id);

        $response->assertStatus(200);
        $response->assertViewIs('player.index');

        $response->assertSee('Os jogadores abaixo estão disponíveis para a confirmação de presença na partida <b>'.$game->label.'</b>. Novos jogadores podem ser cadastrados.', false);
        $response->assertSee('Nenhum jogador disponível para confirmação! Clique no botão acima para adicionar jogadores.');
    }

    public function testIndexAvailablePlayersViewWithPlayers()
    {
        $totalPlayersCount = 10;
        $gamePlayersCount = 5;
        $remainingPlayersCount = $totalPlayersCount - $gamePlayersCount;

        $game = Game::factory()->create();
        $gamePlayers = Player::factory($totalPlayersCount)->create();

        for ($i=0; $i<$gamePlayersCount; $i++) {
            GamePlayer::factory()->create([
                    'team' => null,
                    'game_id' => $game->id,
                    'player_id' => $gamePlayers->pop()->id,
            ]);
        }

        $response = $this->get('/available-players/'.$game->id);

        $response->assertStatus(200);
        $response->assertViewIs('player.index');

        $response->assertSee('Os jogadores abaixo estão disponíveis para a confirmação de presença na partida <b>'.$game->label.'</b>. Novos jogadores podem ser cadastrados.', false);

        $response->assertViewHas('players', function ($viewPlayers) use ($remainingPlayersCount) {
            return $viewPlayers->count() === $remainingPlayersCount;
        });
    }
    public function testIndexAvailablePlayersViewWithNoAvailablePlayers()
    {
        $totalPlayersCount = 10;

        $game = Game::factory()->create();
        $gamePlayers = Player::factory($totalPlayersCount)->create();

        for ($i=0; $i<$totalPlayersCount; $i++) {
            GamePlayer::factory()->create([
                    'team' => null,
                    'game_id' => $game->id,
                    'player_id' => $gamePlayers->pop()->id,
            ]);
        }

        $response = $this->get('/available-players/'.$game->id);

        $response->assertStatus(200);
        $response->assertViewIs('player.index');

        $response->assertSee('Nenhum jogador disponível para confirmação! Clique no botão acima para adicionar jogadores.');

        $response->assertViewHas('players', function ($viewPlayers) {
            return $viewPlayers->count() === 0;
        });
    }

    public function testIndexViewRegister()
    {
        $response = $this->get('/player/create');

        $response->assertStatus(200);
        $response->assertViewIs('player.register');

        $response->assertSee('Cadastre um novo jogador. Ele ficará disponível para diferentes partidas de futebol.');
    }
}
