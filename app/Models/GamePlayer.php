<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamePlayer extends Model
{
    use HasFactory;

    protected $fillable = [
        'rsvp',
        'team',
        'game_id',
        'player_id',
    ];

    /**
     * Get the game associated with game_player.
     */
    public function getGame()
    {
        return $this->hasOne(Game::class, 'id', 'game_id');
    }

    /**
     * Get the player associated with game_player.
     */
    public function getPlayer()
    {
        return $this->hasOne(Player::class, 'id', 'player_id');
    }
}
