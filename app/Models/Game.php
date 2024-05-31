<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Ramsey\Uuid\Uuid;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'label',
        'date',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Autogenerate UUID for the primary key if it's not set
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($game) {
            if (empty($game->id)) {
                $game->id = Uuid::uuid4()->toString();
            }
        });
    }
    
    /**
     * Retrieve players RSVP'd for the game
     */
    public function players(): HasManyThrough
    {
        return $this->hasManyThrough(Player::class, GamePlayer::class, 'game_id', 'id', 'id', 'player_id');
    }
}
