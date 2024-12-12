<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovieResource;
use App\Models\Movie;


class ApiMovieController extends Controller
{
    public function index($cinemaId)
    {
        $movies = Movie::whereHas('sessions', function ($query) use ($cinemaId) {
            $query->whereHas('hall', function ($hallQuery) use ($cinemaId) {
                $hallQuery->where('cinema_id', $cinemaId);
            });
        })->with([
            'sessions' => function ($query) use ($cinemaId) {
                $query->whereHas('hall', function ($hallQuery) use ($cinemaId) {
                    $hallQuery->where('cinema_id', $cinemaId);
                })->orderBy('start_time', 'asc');
            },
            'genres'
        ])->get();

        $movies = $movies->sortBy(function ($movie) {
            return optional($movie->sessions->first())->start_time;
        });

        return MovieResource::collection($movies);
    }
}
