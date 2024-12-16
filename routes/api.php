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
use App\Http\Controllers\Api\PurchaseController;
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

Route::group([], function () {
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

    Route::get('movies/{movieId}/comments', [ApiMovieCommentController::class, 'index']);
    Route::get('movies/{movieId}/ratings', [ApiMovieRatingController::class, 'index']);

    Route::post('/purchases', [PurchaseController::class, 'store']);
    Route::post('/login/qr', [QRCodeController::class, 'login']);
});

Route::middleware('web')->group(function () {
    Route::post('/login', [ApiAuthController::class, 'login']);
    Route::post('/register', [ApiAuthController::class, 'register']);

    Route::get('/auth/google/redirect', [GoogleController::class, 'redirectToGoogle']);
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/', [ApiUserController::class, 'show']);
        Route::post('/profile', [ApiUserController::class, 'updateProfile']);
        Route::post('/password', [ApiUserController::class, 'updatePassword']);
        Route::get('/purchases', [ApiUserController::class, 'getPurchases']);
        Route::post('/avatar', [ApiUserController::class, 'updateAvatar']);
    });

    Route::get('/qr-token', [QRCodeController::class, 'generateToken'])->name('qr.token');

    Route::prefix('movies')->group(function () {
        Route::post('{movieId}/comments', [ApiMovieCommentController::class, 'store']);
        Route::delete('comments/{comment}', [ApiMovieCommentController::class, 'destroy']);

        Route::post('{movieId}/ratings', [ApiMovieRatingController::class, 'store']);
    });

    Route::prefix('user-activity')->group(function () {
        Route::get('/', [UserActivityController::class, 'index']);
        Route::post('/', [UserActivityController::class, 'store']);
        Route::get('/recommend-sessions', [UserActivityController::class, 'recommendSessions']);
    });

    Route::post('/logout', [ApiAuthController::class, 'logout']);
});
