<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovieResource;
use App\Models\Movie;


class ApiMovieController extends Controller
{
    public function index()
    {
        $movies = Movie::with(['sessions', 'genres'])->orderBy('created_at', 'desc')->get();
        return MovieResource::collection($movies);
    }
}
