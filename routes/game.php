<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/games', [GameController::class, 'index']);

Route::get('/game/create', [GameController::class, 'create']);

Route::get('/game/{gameId}', [GameController::class, 'show']);

Route::get('/available-players/{gameId}', [GameController::class, 'indexAvailablePlayers']);

Route::post('/game', [GameController::class, 'store']);
