<?php

namespace App\Models;

use GuzzleHttp\Psr7\Query;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'ability',
        'goalkeeper',
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
}
