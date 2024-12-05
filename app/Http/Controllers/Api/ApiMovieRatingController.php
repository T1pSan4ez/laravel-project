<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RatingResource;
use App\Models\Rating;
use App\Models\Movie;
use Illuminate\Http\Request;

class ApiMovieRatingController extends Controller
{
    public function store(Request $request, $movieId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:10',
        ]);

        $movie = Movie::findOrFail($movieId);

        $rating = Rating::updateOrCreate(
            ['user_id' => $request->user()->id, 'movie_id' => $movieId],
            ['rating' => $request->input('rating')]
        );

        return response()->json([
            'message' => 'Rating saved successfully.',
            'rating' => $rating,
        ], 201);
    }

    public function index($movieId)
    {
        $movie = Movie::findOrFail($movieId);

        $ratings = $movie->ratings()->with('user')->get();

        return response()->json([
            'ratings' => RatingResource::collection($ratings),
            'average_rating' => $movie->ratings()->avg('rating'),
        ]);
    }
}
