<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Models\Movie;
use App\Repositories\MovieRepositoryInterface;
use Illuminate\Http\Request;

class MovieAdminController extends Controller
{
    protected $movieRepository;

    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function index()
    {
        $movies = $this->movieRepository->getAllMovies();
        $genres = $this->movieRepository->getMovieGenres();
        return view('admin.movies', compact('movies', 'genres'));
    }

    public function store(StoreMovieRequest $request)
    {
        $data = $request->validated();
        $genres = $request->input('genres', []);
        $this->movieRepository->createMovie($data, $genres);

        return redirect()->route('movies')->with('success', 'Movie added successfully!');
    }

    public function edit($id)
    {
        $movie = Movie::with('genres')->findOrFail($id);
        $genres = $this->movieRepository->getMovieGenres();
        return view('admin.movies-edit', compact('movie', 'genres'));
    }

    public function update(StoreMovieRequest $request, $id)
    {
        $data = $request->validated();
        $genres = $request->input('genres', []);

        try {
            $this->movieRepository->updateMovie($id, $data, $genres);
            return redirect()->route('movies')->with('success', 'Movie updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('movies.edit', $id)->withErrors(['poster' => 'The uploaded file is too large.']);
        }
    }

    public function destroy($id)
    {
        $this->movieRepository->deleteMovie($id);
        return redirect()->route('movies')->with('success', 'Movie deleted successfully!');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $movies = $this->movieRepository->searchMovies($query);
        return view('admin.movies', compact('movies', 'query'));
    }
}
