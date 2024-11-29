<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovieResource;
use App\Models\Movie;


class ApiMovieController extends Controller
{
    public function index($cinemaId)
    {
        $movies = Movie::whereHas('sessions', function ($query) use ($cinemaId) {
            $query->whereHas('hall.slots.sessionSlots', function ($slotQuery) {
                $slotQuery->whereHas('session');
            })->whereHas('hall', function ($hallQuery) use ($cinemaId) {
                $hallQuery->where('cinema_id', $cinemaId);
            });
        })->with([
            'sessions' => function ($query) use ($cinemaId) {
                $query->whereHas('hall.slots.sessionSlots', function ($slotQuery) {
                    $slotQuery->whereHas('session');
                })->whereHas('hall', function ($hallQuery) use ($cinemaId) {
                    $hallQuery->where('cinema_id', $cinemaId);
                });
            },
            'genres'
        ])->orderBy('created_at', 'desc')->get();

        return MovieResource::collection($movies);
    }
}
