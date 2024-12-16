<?php

namespace App\Repositories;

use App\Interfaces\ApiMovieRepositoryInterface;
use App\Models\Movie;
use Illuminate\Support\Collection;

class ApiMovieRepository implements ApiMovieRepositoryInterface
{
    public function getMoviesByCinema(int $cinemaId): Collection
    {
        $today = now()->toDateString();

        $movies = Movie::whereHas('sessions', function ($query) use ($cinemaId, $today) {
            $query->whereHas('hall', function ($hallQuery) use ($cinemaId) {
                $hallQuery->where('cinema_id', $cinemaId);
            })
                ->where(function ($sessionQuery) use ($today) {
                    $sessionQuery->whereDate('start_time', '>=', $today)
                    ->orWhereDate('start_time', $today);
                });
        })->with([
            'sessions' => function ($query) use ($cinemaId, $today) {
                $query->whereHas('hall', function ($hallQuery) use ($cinemaId) {
                    $hallQuery->where('cinema_id', $cinemaId);
                })
                    ->where(function ($sessionQuery) use ($today) {
                        $sessionQuery->whereDate('start_time', '>=', $today)
                            ->orWhereDate('start_time', $today);
                    })->orderBy('start_time', 'asc');
            },
            'genres'
        ])->get();

        return $movies->sortBy(function ($movie) {
            return optional($movie->sessions->first())->start_time;
        });
    }

}
