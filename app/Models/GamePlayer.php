<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
    protected static function boot(): void
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
    public function game(): HasOne
    {
        return $this->hasOne(Game::class, 'id', 'game_id');
    }

    /**
     * Get the player associated with game_player.
     */
    public function player(): HasOne
    {
        return $this->hasOne(Player::class, 'id', 'player_id');
    }

    public static function teamsByGameId(string $gameId): array
    {
        $playersByGame = GamePlayer::select()
            ->join('players', 'players.id', '=', 'player_id')
            ->where('game_id', $gameId)
            ->orderByDesc('players.ability')
            ->get()
        ;

        $teams = [];
        $playersCount = $playersByGame->count();

        for ($i=0; $i<$playersCount; $i++) {
            $team = $playersByGame[$i]['team'];

            if ($team === null) {
                continue;
            }

            $teams[$playersByGame[$i]['team']][] = $playersByGame[$i];
        }
        
        return $teams;
    }
}
