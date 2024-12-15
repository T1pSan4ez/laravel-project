<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovieResource;
use App\Interfaces\ApiMovieRepositoryInterface;

class ApiMovieController extends Controller
{
    protected $apiMovieRepository;

    public function __construct(ApiMovieRepositoryInterface $apiMovieRepository)
    {
        $this->apiMovieRepository = $apiMovieRepository;
    }

    public function index($cinemaId)
    {
        $movies = $this->apiMovieRepository->getMoviesByCinema($cinemaId);

        return MovieResource::collection($movies);
    }
}
