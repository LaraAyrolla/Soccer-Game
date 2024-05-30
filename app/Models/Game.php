<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($game) {
            if (empty($game->id)) {
                $game->id = Uuid::uuid4()->toString();
            }
        });
    }
}
