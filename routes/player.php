<?php

use App\Http\Controllers\PlayerController;
use Illuminate\Support\Facades\Route;

Route::get('/player/create', [PlayerController::class, 'create']);

Route::post('/player',[PlayerController::class, 'store']);
