<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class GamePlayer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'team',
        'game_id',
        'player_id',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Autogenerate UUID for the primary key if it's not set
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($player) {
            if (empty($player->id)) {
                $player->id = Uuid::uuid4()->toString();
            }
        });
    }

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
