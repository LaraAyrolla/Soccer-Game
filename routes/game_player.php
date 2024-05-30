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

/**
 * Create RSVP route.
 *
 * This route responds to POST requests for the RSVP of a player in a certain game.
 */
Route::patch(
    '/rsvp/{gameId}/{playerId}',
    [TeamController::class, 'updateRsvp']
);
