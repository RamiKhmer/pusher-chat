<?php

use App\Http\Controllers\PusherController;
use Illuminate\Support\Facades\Route;


Route::get('/', [PusherController::class, 'index']);
Route::post('/broadcast', [PusherController::class, 'broadcast']);
Route::post('/receive', [PusherController::class, 'receive']);
