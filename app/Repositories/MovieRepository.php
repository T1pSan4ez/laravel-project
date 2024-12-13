<?php

namespace App\Repositories;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;

class MovieRepository implements MovieRepositoryInterface
{
    public function getAllMovies(): Paginator
    {
        return Movie::orderBy('created_at', 'desc')->paginate(5);
    }

    public function getMovieGenres()
    {
        return Genre::all();
    }

    public function createMovie(array $data, array $genres): mixed
    {
        if (isset($data['poster'])) {
            $data['poster'] = $data['poster']->store('posters', 'public');
        }

        $movie = Movie::create($data);

        if (!empty($genres)) {
            $movie->genres()->sync($genres);
        }

        return $movie;
    }

    public function updateMovie(int $id, array $data, array $genres): bool
    {
        $movie = Movie::findOrFail($id);

        if (isset($data['poster'])) {
            if ($movie->poster) {
                Storage::disk('public')->delete($movie->poster);
            }
            $data['poster'] = $data['poster']->store('posters', 'public');
        }

        $movie->update($data);

        if (!empty($genres)) {
            $movie->genres()->sync($genres);
        }

        return true;
    }

    public function deleteMovie(int $id): bool
    {
        $movie = Movie::findOrFail($id);
        $movie->delete();
        return true;
    }

    public function searchMovies(string $query): Paginator
    {
        return Movie::where('title', 'like', '%' . $query . '%')->paginate(5);
    }
}
