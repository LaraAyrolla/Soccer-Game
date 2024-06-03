<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexByGameViewWithNoTeams()
    {
        $game = Game::factory()->create();

        $gamePlayers = Player::factory(10)->create();

        for ($i=0; $i<10; $i++) {
            GamePlayer::factory()->create([
                    'team' => null,
                    'game_id' => $game->id,
                    'player_id' => $gamePlayers->pop()->id,
            ]);
        }

        $response = $this->get('/team/index/'.$game->id);

        $response->assertStatus(200);
        $response->assertViewIs('team.index');

        $response->assertSee('Visualizando equipes para a partida: <b>'.$game->label.'</b>', false);
        $response->assertSee('Nenhuma equipe gerada para a partida! Clique no botão acima para gerar as equipes automaticamente.');
    }

    public function testIndexByGameViewWithNoPlayersConfirmed()
    {
        $game = Game::factory()->create();

        $response = $this->get('/team/index/'.$game->id);

        $response->assertStatus(302);

        $response->assertSessionHasErrors();
        $response->assertSessionHasErrorsIn('São necessários dois os mais jogadores confirmados para gerar as equipes! Por favor, confirme a presença de mais jogadores antes de prosseguir.');
    }

    public function testIndexByGameViewWithTwoTeams()
    {
        $game = Game::factory()->create();

        $gamePlayers = Player::factory(10)->create();

        for ($i=0; $i<5; $i+=2) {
            GamePlayer::factory()->create([
                    'team' => 1,
                    'game_id' => $game->id,
                    'player_id' => $gamePlayers->pop()->id,
            ]);

            GamePlayer::factory()->create([
                    'team' => 2,
                    'game_id' => $game->id,
                    'player_id' => $gamePlayers->pop()->id,
            ]);
        }

        $response = $this->get('/team/index/'.$game->id);

        $response->assertStatus(200);
        $response->assertViewIs('team.index');

        $response->assertSee('Visualizando equipes para a partida: <b>'.$game->label.'</b>', false);
        $response->assertSee('Time 1:');
        $response->assertSee('Time 2:');

        $response->assertViewHas('teams', function ($viewTeams) {
            return count($viewTeams) === 2;
        });
    }

    public function testIndexByGameViewWithTwoTeamsAndBench()
    {
        $game = Game::factory()->create();

        $gamePlayers = Player::factory(18)->create();

        for ($i=0; $i<18; $i+=3) {
            GamePlayer::factory()->create([
                    'team' => 1,
                    'game_id' => $game->id,
                    'player_id' => $gamePlayers->pop()->id,
            ]);

            GamePlayer::factory()->create([
                    'team' => 2,
                    'game_id' => $game->id,
                    'player_id' => $gamePlayers->pop()->id,
            ]);

            GamePlayer::factory()->create([
                    'team' => 3,
                    'game_id' => $game->id,
                    'player_id' => $gamePlayers->pop()->id,
            ]);
        }

        $response = $this->get('/team/index/'.$game->id);

        $response->assertStatus(200);
        $response->assertViewIs('team.index');

        $response->assertSee('Visualizando equipes para a partida: <b>'.$game->label.'</b>', false);
        $response->assertSee('Time 1:');
        $response->assertSee('Time 2:');
        $response->assertSee('Banco de Reservas:');

        $response->assertViewHas('teams', function ($viewTeams) {
            return count($viewTeams) === 3;
        });
        $response->assertViewHas('teams', function ($viewTeams) {
            return count($viewTeams[1]) === 6;
        });
        $response->assertViewHas('teams', function ($viewTeams) {
            return count($viewTeams[2]) === 6;
        });
        $response->assertViewHas('teams', function ($viewTeams) {
            return count($viewTeams[3]) === 6;
        });
    }
}
