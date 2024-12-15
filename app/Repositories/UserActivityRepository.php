<?php

namespace App\Repositories;

use App\Interfaces\UserActivityRepositoryInterface;
use App\Models\Movie;
use App\Models\Session;
use App\Models\UserActivity;
use Illuminate\Support\Collection;

class UserActivityRepository implements UserActivityRepositoryInterface
{
    public function getUserActivitiesMovieIds(int $userId): array
    {
        return UserActivity::where('user_id', $userId)
            ->pluck('movie_id')
            ->toArray();
    }

    public function getUserMovieGenres(array $movieIds): array
    {
        return Movie::whereIn('id', $movieIds)
            ->with('genres')
            ->get()
            ->pluck('genres')
            ->flatten()
            ->unique('id')
            ->pluck('id')
            ->toArray();
    }

    public function getRecommendedMovies(array $genreIds, array $excludedMovieIds, int $limit = 6): Collection
    {
        return Movie::whereHas('genres', function ($query) use ($genreIds) {
            $query->whereIn('genres.id', $genreIds);
        })
            ->whereNotIn('id', $excludedMovieIds)
            ->with('genres')
            ->distinct()
            ->limit($limit)
            ->get();
    }

    public function createActivity(array $data)
    {
        return UserActivity::create($data);
    }

    public function findExistingActivity(int $userId, int $movieId, string $action)
    {
        return UserActivity::where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->where('action', $action)
            ->first();
    }

    public function getViewedMovies(int $userId): array
    {
        return UserActivity::where('user_id', $userId)
            ->where('action', 'view')
            ->pluck('movie_id')
            ->toArray();
    }

    public function getBookedMovies(int $userId): array
    {
        return UserActivity::where('user_id', $userId)
            ->where('action', 'booking')
            ->pluck('movie_id')
            ->toArray();
    }

    public function getRecommendedSessions(array $movieIds): Collection
    {
        return Session::with(['hall.cinema.city', 'movie'])
            ->whereIn('movie_id', $movieIds)
            ->where('start_time', '>=', now())
            ->get();
    }
}
