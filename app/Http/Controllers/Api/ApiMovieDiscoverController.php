<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FilmResource;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\Request;

class ApiMovieDiscoverController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', '%' . $search . '%');
        }

        if ($request->has('genre_ids')) {
            $genreIds = $request->input('genre_ids');
            $query->whereHas('genres', function ($genreQuery) use ($genreIds) {
                $genreQuery->whereIn('genres.id', $genreIds);
            });
        }

        if ($request->has('sort_by') && $request->input('sort_by') === 'release_date') {
            $query->orderBy('release_date', $request->input('order', 'desc'));
        }

        $movies = $query->paginate(9);

        return FilmResource::collection($movies);
    }

    public function show($id)
    {
        $movie = Movie::with('genres')->findOrFail($id);

        return new FilmResource($movie);
    }

    public function genres()
    {
        $genres = Genre::all();

        return response()->json($genres);
    }
}
