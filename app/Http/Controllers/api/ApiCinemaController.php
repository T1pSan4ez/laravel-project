<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\User;
use App\Models\City;
use App\Models\Cinema;
use App\Models\Hall;
use App\Models\Movie;
use App\Models\Session;
use Illuminate\Http\Request;

class ApiCinemaController extends Controller
{
    public function index()
    {
        $cities = City::with('cinemas')->orderBy('name', 'asc')->get();
        return CityResource::collection($cities);
    }
}
