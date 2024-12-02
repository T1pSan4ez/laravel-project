<?php

namespace App\Http\Controllers\Api;

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
        $cities = City::whereHas('cinemas.halls.slots.sessionSlots', function ($query) {
            $query->whereHas('session');
        })->with([
            'cinemas' => function ($cinemaQuery) {
                $cinemaQuery->whereHas('halls.slots.sessionSlots', function ($query) {
                    $query->whereHas('session');
                })->with([
                    'halls' => function ($hallQuery) {
                        $hallQuery->whereHas('slots.sessionSlots', function ($query) {
                            $query->whereHas('session');
                        })->with(['slots' => function ($slotQuery) {
                            $slotQuery->whereHas('sessionSlots', function ($query) {
                                $query->whereHas('session');
                            });
                        }]);
                    }
                ]);
            }
        ])->orderBy('name', 'asc')->get();

        return CityResource::collection($cities);
    }
}
