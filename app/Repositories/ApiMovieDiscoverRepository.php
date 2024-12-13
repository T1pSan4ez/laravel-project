<?php

namespace App\Repositories;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ApiMovieDiscoverRepository implements ApiMovieDiscoverRepositoryInterface
{
    public function getMovies(array $filters): LengthAwarePaginator
    {
        $query = Movie::query();

        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['genre_ids'])) {
            $genreIds = $filters['genre_ids'];
            $query->whereHas('genres', function ($genreQuery) use ($genreIds) {
                $genreQuery->whereIn('genres.id', $genreIds);
            });
        }

        if (!empty($filters['sort_by']) && $filters['sort_by'] === 'release_date') {
            $query->orderBy('release_date', $filters['order'] ?? 'desc');
        }

        return $query->paginate(9);
    }

    public function getMovieById(int $id): object
    {
        return Movie::with('genres')->findOrFail($id);
    }

    public function getAllGenres(): Collection
    {
        return Genre::all();
    }
}
