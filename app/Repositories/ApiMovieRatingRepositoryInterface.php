<?php

namespace App\Repositories;

use App\Models\Rating;
use Illuminate\Support\Collection;

interface ApiMovieRatingRepositoryInterface
{
    public function saveRating(int $userId, int $movieId, int $ratingValue): Rating;
    public function getMovieRatings(int $movieId): Collection;
    public function getMovieAverageRating(int $movieId): float;
}
