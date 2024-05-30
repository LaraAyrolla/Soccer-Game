<?php

use App\Http\Controllers\PlayerController;
use Illuminate\Support\Facades\Route;

/**
 * Create new player route.
 *
 * This route responds to POST requests for creating a new player.
 */
Route::post(
    '/player',
    [PlayerController::class, 'store']
);
