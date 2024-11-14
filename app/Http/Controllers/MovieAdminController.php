<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieAdminController extends Controller
{
    public function index()
    {
        return view('admin/movies');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer',
            'age_rating' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:255',
            'release_date' => 'nullable|date',
        ]);

        Movie::create($validatedData);

        return redirect()->route('movies')->with('success', 'Movie added successfully!');
    }
}
