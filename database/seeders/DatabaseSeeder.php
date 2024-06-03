<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Player;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Game::factory()->create(['label' => 'Jogo Legal']);

        $game = Game::factory()->create(['label' => 'Joguinho']);
        $players = Player::factory(15)->create();

        for ($i=0; $i<10; $i++) {
            GamePlayer::factory()->create([
                    'team' => null,
                    'game_id' => $game->id,
                    'player_id' => $players->pop()->id,
            ]);
        }
    }
}
