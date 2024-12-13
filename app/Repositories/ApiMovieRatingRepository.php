<?php

namespace App\Repositories;

use App\Models\Rating;
use App\Models\Movie;
use Illuminate\Support\Collection;

class ApiMovieRatingRepository implements ApiMovieRatingRepositoryInterface
{
    public function saveRating(int $userId, int $movieId, int $ratingValue): Rating
    {
        return Rating::updateOrCreate(
            ['user_id' => $userId, 'movie_id' => $movieId],
            ['rating' => $ratingValue]
        );
    }

    public function getMovieRatings(int $movieId): Collection
    {
        $movie = Movie::findOrFail($movieId);
        return $movie->ratings()->with('user')->get();
    }

    public function getMovieAverageRating(int $movieId): float
    {
        $movie = Movie::findOrFail($movieId);
        return (float) $movie->ratings()->avg('rating');
    }
}
