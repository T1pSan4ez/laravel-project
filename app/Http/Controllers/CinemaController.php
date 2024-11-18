<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCityRequest;
use App\Http\Requests\AddCinemaRequest;
use App\Models\Cinema;
use App\Models\City;

class CinemaController extends Controller
{
    public function index()
    {
        $cities = City::with('cinemas')->orderBy('name', 'asc')->get();

        return view('admin/cinemas', compact('cities'));
    }

    public function addCity(AddCityRequest $request)
    {
        City::create([
            'name' => $request->city_name,
        ]);

        return redirect()->route('cinemas')->with('success', 'City added successfully.');
    }

    public function addCinema(AddCinemaRequest $request)
    {
        Cinema::create([
            'city_id' => $request->city_id,
            'name' => $request->cinema_name,
            'address' => $request->cinema_address,
        ]);

        return redirect()->route('cinemas')->with('success', 'Cinema added successfully.');
    }

    public function deleteCity(City $city)
    {
        $city->delete();

        return redirect()->route('cinemas')->with('success', 'City deleted successfully.');
    }

    public function deleteCinema(Cinema $cinema)
    {
        $cinema->delete();

        return redirect()->route('cinemas')->with('success', 'Cinema deleted successfully.');
    }
}
