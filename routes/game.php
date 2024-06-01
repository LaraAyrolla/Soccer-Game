<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/games',[GameController::class, 'index']);

Route::get('/game/create',[GameController::class, 'create']);

Route::post('/game', [GameController::class, 'store']);
