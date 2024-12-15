<?php

namespace App\Repositories;

use App\Interfaces\ApiMovieRepositoryInterface;
use App\Models\Movie;
use Illuminate\Support\Collection;

class ApiMovieRepository implements ApiMovieRepositoryInterface
{
    public function getMoviesByCinema(int $cinemaId): Collection
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

        return $movies->sortBy(function ($movie) {
            return optional($movie->sessions->first())->start_time;
        });
    }
}
