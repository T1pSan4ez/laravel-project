<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Models\Movie;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieAdminController extends Controller
{
    public function index()
    {
        $movies = Movie::orderBy('created_at', 'desc')->paginate(5);
        return view('admin.movies', compact('movies'));
    }

    public function store(StoreMovieRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('poster')) {
                $data['poster'] = $request->file('poster')->store('posters', 'public');
            }

            Movie::create($data);
            return redirect()->route('movies')->with('success', 'Movie added successfully!');
        } catch (PostTooLargeException $e) {
            return redirect()->route('movies')->withErrors(['poster' => 'The uploaded file is too large. The maximum file size is 2MB.']);
        }
    }

    public function edit($id)
    {
        $movie = Movie::findOrFail($id);
        return view('admin.movies-edit', compact('movie'));
    }

    public function update(StoreMovieRequest $request, $id)
    {
        try {
            $movie = Movie::findOrFail($id);
            $data = $request->validated();

            if ($request->hasFile('poster')) {
                if ($movie->poster) {
                    Storage::disk('public')->delete($movie->poster);
                }
                $data['poster'] = $request->file('poster')->store('posters', 'public');
            }

            $movie->update($data);
            return redirect()->route('movies')->with('success', 'Movie updated successfully!');
        } catch (PostTooLargeException $e) {
            return redirect()->route('movies.edit', $id)->withErrors(['poster' => 'The uploaded file is too large. Maximum file size is 2MB.']);
        }
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
