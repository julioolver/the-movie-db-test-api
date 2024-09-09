<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'movies'
], function () {
    Route::get('/', [MovieController::class, 'index']);
    Route::post('/', [MovieController::class, 'store']);
    Route::put('{movie}/status', [MovieController::class, 'updateStatus']);
    Route::get('{id}', [MovieController::class, 'getMovieDetails']);
});

Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'user'
], function () {
    Route::get('movies', [MovieController::class, 'getUserMovies']);
});
