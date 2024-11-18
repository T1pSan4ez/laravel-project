<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieAdminController extends Controller
{
    public function index()
    {
        $movies = Movie::orderBy('created_at', 'desc')->paginate(5);
        return view('admin.movies', compact('movies'));
    }

    public function store(StoreMovieRequest $request)
    {
        Movie::create($request->validated());
        return redirect()->route('movies')->with('success', 'Movie added successfully!');
    }

    public function edit($id)
    {
        $movie = Movie::findOrFail($id);
        return view('admin.movies-edit', compact('movie'));
    }

    public function update(StoreMovieRequest $request, $id)
    {
        $movie = Movie::findOrFail($id);
        $movie->update($request->validated());
        return redirect()->route('movies')->with('success', 'Movie updated successfully!');
    }

    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);
        $movie->delete();
        return redirect()->route('movies')->with('success', 'Movie deleted successfully!');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $movies = Movie::where('title', 'like', '%' . $query . '%')
            ->paginate(5);

        return view('admin.movies', compact('movies', 'query'));
    }
}
