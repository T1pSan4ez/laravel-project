<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\City;
use Illuminate\Http\Request;

class CinemaController extends Controller
{
    public function index()
    {
        $cities = City::with('cinemas')->get();

        return view('admin/cinemas', compact('cities'));
    }

    public function addCity(Request $request)
    {
        $request->validate([
            'city_name' => 'required|string|max:255',
        ]);

        City::create([
            'name' => $request->city_name,
        ]);

        return redirect()->route('cinemas')->with('success', 'City added successfully.');
    }

    public function addCinema(Request $request)
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'cinema_name' => 'required|string|max:255',
            'cinema_address' => 'required|string|max:255',
        ]);

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
