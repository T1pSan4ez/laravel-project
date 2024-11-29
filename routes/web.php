<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CinemaController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\MovieAdminController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TheaterAdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('layouts.main');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin-panel/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('/admin-panel/movies')->group(function () {
        Route::get('/', [MovieAdminController::class, 'index'])->name('movies');
        Route::post('/', [MovieAdminController::class, 'store'])->name('movies.store');
        Route::get('/edit/{id}', [MovieAdminController::class, 'edit'])->name('movies.edit');
        Route::put('/{id}', [MovieAdminController::class, 'update'])->name('movies.update');
        Route::delete('/{id}', [MovieAdminController::class, 'destroy'])->name('movies.destroy');
        Route::get('/search', [MovieAdminController::class, 'search'])->name('movies.search');
    });

    Route::get('/admin-panel/theater-plays', [TheaterAdminController::class, 'index'])->name('theater-plays');

    Route::get('/admin-panel/cinemas', [CinemaController::class, 'index'])->name('cinemas');
    Route::post('/admin/halls/add-city', [CinemaController::class, 'addCity'])->name('cinemas.addCity');
    Route::post('/admin/halls/add-cinema', [CinemaController::class, 'addCinema'])->name('cinemas.addCinema');
    Route::delete('/admin/halls/delete-city/{city}', [CinemaController::class, 'deleteCity'])->name('cinemas.deleteCity');
    Route::delete('/admin/halls/delete-cinema/{cinema}', [CinemaController::class, 'deleteCinema'])->name('cinemas.deleteCinema');

    Route::get('/admin-panel/cinema/{cinema_id}/halls', [HallController::class, 'index'])->name('halls');
    Route::post('/admin-panel/cinema/{cinema_id}/halls', [HallController::class, 'store'])->name('halls.store');
    Route::delete('/admin-panel/cinema/{cinema_id}/halls/{hall_id}', [HallController::class, 'destroy'])->name('halls.destroy');
    Route::get('/admin-panel/cinema/{cinema_id}/halls/{hall_id}/edit', [HallController::class, 'edit'])->name('halls.edit');
    Route::post('/admin-panel/cinema/{cinema_id}/halls/{hall_id}/update-seats', [HallController::class, 'updateSeats'])->name('halls.updateSeats');
    Route::post('/admin-panel/cinema/{cinema_id}/halls/{hall_id}/clear-seats', [HallController::class, 'clearSeats'])->name('halls.clearSeats');

    Route::prefix('/admin-panel/sessions')->group(function () {
        Route::get('/', [SessionController::class, 'index'])->name('sessions');
        Route::post('/', [SessionController::class, 'store'])->name('sessions.store');
        Route::get('/search', [SessionController::class, 'search'])->name('sessions.search');
        Route::get('/{id}/edit', [SessionController::class, 'edit'])->name('sessions.edit');
        Route::put('/{id}', [SessionController::class, 'update'])->name('sessions.update');
        Route::delete('/{id}', [SessionController::class, 'destroy'])->name('sessions.destroy');
    });

    Route::prefix('/admin-panel/products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('products');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::post('/', [ProductController::class, 'store'])->name('products.store');
        Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });



    Route::get('/admin-panel/users', [UserController::class, 'index'])->name('users');
});
