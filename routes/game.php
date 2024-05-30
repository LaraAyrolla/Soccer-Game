<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

/**
 * Create new game route.
 *
 * This route responds to POST requests for creating a new soccer game.
 */
Route::post(
    '/game',
    [GameController::class, 'store']
);
