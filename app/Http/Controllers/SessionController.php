<?php

namespace App\Http\Controllers;

use App\Http\Requests\SessionRequest;
use App\Models\City;
use App\Models\Movie;
use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index()
    {
        $movies = Movie::orderBy('created_at', 'desc')->paginate(5);
        $cities = City::with('cinemas.halls')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.sessions', [
            'movies' => $movies,
            'cities' => $cities,
        ]);
    }

    public function store(SessionRequest $request)
    {
        Session::create($request->validated());

        return redirect()->route('sessions')->with('success', 'Session created successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $movies = Movie::where('title', 'like', '%' . $query . '%')
            ->paginate(5);

        return view('admin.sessions', compact('movies', 'query'));
    }
}
