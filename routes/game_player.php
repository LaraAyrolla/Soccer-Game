<?php

use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

/**
 * Create new game_player route.
 *
 * This route responds to POST requests for creating two new teams for a soccer game.
 */
Route::post(
    '/teams',
    [TeamController::class, 'store']
);
