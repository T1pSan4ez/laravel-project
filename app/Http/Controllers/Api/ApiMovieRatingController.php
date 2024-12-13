<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieRatingRequest;
use App\Http\Resources\RatingResource;
use App\Repositories\ApiMovieRatingRepositoryInterface;


class ApiMovieRatingController extends Controller
{
    protected $repository;

    public function __construct(ApiMovieRatingRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function store(StoreMovieRatingRequest $request, $movieId)
    {
        $rating = $this->repository->saveRating(
            $request->user()->id,
            $movieId,
            $request->input('rating')
        );

        return response()->json([
            'message' => 'Rating saved successfully.',
            'rating' => new RatingResource($rating),
        ], 201);
    }

    public function index($movieId)
    {
        $ratings = $this->repository->getMovieRatings($movieId);
        $averageRating = $this->repository->getMovieAverageRating($movieId);

        return response()->json([
            'ratings' => RatingResource::collection($ratings),
            'average_rating' => $averageRating,
        ]);
    }
}
