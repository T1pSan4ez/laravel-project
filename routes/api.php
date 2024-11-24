<?php

use App\Http\Controllers\api\ApiAuthController;
use App\Http\Controllers\api\ApiCinemaController;
use App\Http\Controllers\api\ApiMovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/cities', [ApiCinemaController::class, 'index']);
Route::get('/movies', [ApiMovieController::class, 'index']);

Route::middleware('web')->group(function () {
    Route::post('/login', [ApiAuthController::class, 'login']);
    Route::post('/register', [ApiAuthController::class, 'register']);
});

Route::middleware('auth:sanctum')->post('/logout', [ApiAuthController::class, 'logout']);
