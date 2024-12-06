<?php

use App\Http\Controllers\Api\ApiCinemaController;
use App\Http\Controllers\Api\ApiMovieCommentController;
use App\Http\Controllers\Api\ApiMovieController;
use App\Http\Controllers\Api\ApiMovieDiscoverController;
use App\Http\Controllers\Api\ApiMovieRatingController;
use App\Http\Controllers\Api\ApiPaymentController;
use App\Http\Controllers\Api\ApiProductController;
use App\Http\Controllers\Api\ApiSessionController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\SessionSlotController;
use App\Http\Controllers\Api\UserActivityController;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\QRCodeController;
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

Route::get('/movies', [ApiMovieDiscoverController::class, 'index']);
Route::get('/movie/{id}', [ApiMovieDiscoverController::class, 'show']);
Route::get('/genres', [ApiMovieDiscoverController::class, 'genres']);

Route::middleware('web')->group(function () {
    Route::post('/login', [ApiAuthController::class, 'login']);
    Route::post('/register', [ApiAuthController::class, 'register']);


    Route::get('/auth/google/redirect', [GoogleController::class, 'redirectToGoogle']);
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
});


Route::get('movies/{movieId}/comments', [ApiMovieCommentController::class, 'index']);

Route::get('movies/{movieId}/ratings', [ApiMovieRatingController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [ApiUserController::class, 'show']);
    Route::post('/user/profile', [ApiUserController::class, 'updateProfile']);
    Route::post('/user/password', [ApiUserController::class, 'updatePassword']);

    Route::get('/qr-token', [QRCodeController::class, 'generateToken'])->name('qr.token');

    Route::post('movies/{movieId}/comments', [ApiMovieCommentController::class, 'store']);
    Route::delete('movies/comments/{comment}', [ApiMovieCommentController::class, 'destroy']);

    Route::post('movies/{movieId}/ratings', [ApiMovieRatingController::class, 'store']);

    Route::get('/user-activity', [UserActivityController::class, 'index']);
    Route::post('/user-activity', [UserActivityController::class, 'store']);
    Route::get('/user-activity/recommend-sessions', [UserActivityController::class, 'recommendSessions']);

    Route::post('/logout', [ApiAuthController::class, 'logout']);
});

Route::post('/login/qr', [QRCodeController::class, 'login']);


