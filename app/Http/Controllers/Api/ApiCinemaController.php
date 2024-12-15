<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Interfaces\ApiCinemaRepositoryInterface;

class ApiCinemaController extends Controller
{
    protected $cinemaRepository;

    public function __construct(ApiCinemaRepositoryInterface $cinemaRepository)
    {
        $this->cinemaRepository = $cinemaRepository;
    }

    public function index()
    {
        $cities = $this->cinemaRepository->getCitiesWithSessions();
        return CityResource::collection($cities);
    }
}
