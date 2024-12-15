<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FilmResource;
use App\Interfaces\ApiMovieDiscoverRepositoryInterface;
use Illuminate\Http\Request;

class ApiMovieDiscoverController extends Controller
{
    protected $repository;

    public function __construct(ApiMovieDiscoverRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'genre_ids', 'sort_by', 'order']);
        $movies = $this->repository->getMovies($filters);

        return FilmResource::collection($movies);
    }

    public function show($id)
    {
        $movie = $this->repository->getMovieById($id);

        return new FilmResource($movie);
    }

    public function genres()
    {
        $genres = $this->repository->getAllGenres();

        return response()->json($genres);
    }
}
