<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface UserActivityRepositoryInterface
{
    public function getUserActivitiesMovieIds(int $userId): array;

    public function getUserMovieGenres(array $movieIds): array;

    public function getRecommendedMovies(array $genreIds, array $excludedMovieIds, int $limit = 6): Collection;

    public function createActivity(array $data);

    public function findExistingActivity(int $userId, int $movieId, string $action);

    public function getViewedMovies(int $userId): array;

    public function getBookedMovies(int $userId): array;

    public function getRecommendedSessions(array $movieIds): Collection;
}
