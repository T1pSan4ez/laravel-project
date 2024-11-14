<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CinemaController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\MovieAdminController;
use App\Http\Controllers\TheaterAdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('/admin-panel', function () {
    return view('layouts.main');
});

Route::get('/admin-panel/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/admin-panel/movies', [MovieAdminController::class, 'index'])->name('movies');
Route::post('/admin-panel/movies', [MovieAdminController::class, 'store'])->name('movies.store');

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


Route::get('/admin-panel/users', [UserController::class, 'index'])->name('users');
