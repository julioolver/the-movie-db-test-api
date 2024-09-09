<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
});

Route::post('/auth/register', [AuthController::class, 'register']);

Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'movies'
], function ($router) {
    Route::get('/', [MovieController::class, 'index']);
    Route::post('/', [MovieController::class, 'store']);
    Route::put('{movie}/status', [MovieController::class, 'updateStatus']);
    Route::get('/movies/{id}', [MovieController::class, 'getMovieDetails']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('user/movies', [MovieController::class, 'getUserMovies']);
});
