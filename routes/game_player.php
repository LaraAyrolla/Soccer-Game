<?php

use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/team/index/{gameId}', [TeamController::class, 'indexByGame']);

Route::post('/rsvp', [TeamController::class, 'store']);

Route::patch('/teams', [TeamController::class, 'update']);
