<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexViewWithNoGames()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('game.index');

        $response->assertSee('Crie novas partidas. Jogadores podem ser cadastrados durante a confirmação de presença.');
        $response->assertSee('Nenhuma partida cadastrada! Clique no botão acima para adicionar partidas.');
    }

    public function testIndexViewWithGames()
    {
        $games = Game::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('game.index');

        $response->assertSee('Crie novas partidas. Jogadores podem ser cadastrados durante a confirmação de presença.');

        $response->assertViewHas('games', function ($viewUsers) use ($games) {
            return $viewUsers->count() === $games->count();
        });
    }

    public function testIndexConfirmedPlayersViewWithNoPlayers()
    {
        $game = Game::factory()->create();

        $response = $this->get('/game/'.$game->id);

        $response->assertStatus(200);
        $response->assertViewIs('game.players');

        $response->assertSee('Gerenciando a partida: <b>'.$game->label.'</b>', false);
        $response->assertSee('Nenhum jogador confirmado para a partida! Clique no botão acima para adicionar jogadores.');
    }

    public function testIndexConfirmedPlayersViewWithPlayers()
    {
        $playersCount = 10;
        $game = Game::factory()->create();
        $gamePlayers = Player::factory($playersCount)->create();

        for ($i=0; $i<$playersCount; $i++) {
            GamePlayer::factory()->create([
                    'team' => null,
                    'game_id' => $game->id,
                    'player_id' => $gamePlayers->pop()->id,
            ]);
        }

        $response = $this->get('/game/'.$game->id);

        $response->assertStatus(200);
        $response->assertViewIs('game.players');

        $response->assertSee('Gerenciando a partida: <b>'.$game->label.'</b>', false);

        $response->assertViewHas('gamePlayers', function ($viewGamePlayers) use ($playersCount) {
            return $viewGamePlayers->count() === $playersCount;
        });
    }

    public function testIndexViewRegister()
    {
        $response = $this->get('/game/create');

        $response->assertStatus(200);
        $response->assertViewIs('game.register');

        $response->assertSee('Crie uma nova partida de futebol.');
    }
}
