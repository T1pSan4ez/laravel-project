<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiCinemaController;
use App\Http\Controllers\Api\ApiMovieController;
use App\Http\Controllers\Api\ApiPaymentController;
use App\Http\Controllers\Api\ApiProductController;
use App\Http\Controllers\Api\ApiSessionController;
use App\Http\Controllers\Api\SessionSlotController;
use App\Http\Controllers\Auth\GoogleController;
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
Route::get('/movies/{cinemaId}', [ApiMovieController::class, 'index']);
Route::get('/sessions', [ApiMovieController::class, 'index']);
Route::get('/sessions/{id}', [ApiSessionController::class, 'index']);
Route::get('/products', [ApiProductController::class, 'index']);
Route::patch('/session-slots/{session_id}', [SessionSlotController::class, 'updateStatuses']);
Route::post('/payments', [ApiPaymentController::class, 'store']);

Route::middleware('web')->group(function () {
    Route::post('/login', [ApiAuthController::class, 'login']);
    Route::post('/register', [ApiAuthController::class, 'register']);
//    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
//    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

});

Route::middleware('auth:sanctum')->post('/logout', [ApiAuthController::class, 'logout']);

