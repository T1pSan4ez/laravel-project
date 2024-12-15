<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCinemaRequest;
use App\Http\Requests\AddCityRequest;
use App\Interfaces\CinemaRepositoryInterface;

class CinemaController extends Controller
{
    protected $cinemaRepository;

    public function __construct(CinemaRepositoryInterface $cinemaRepository)
    {
        $this->cinemaRepository = $cinemaRepository;
    }

    public function index()
    {
        $cities = $this->cinemaRepository->getAllCitiesWithCinemas();

        return view('admin.cinemas', compact('cities'));
    }

    public function addCity(AddCityRequest $request)
    {
        $this->cinemaRepository->addCity($request->city_name);

        return redirect()->route('cinemas')->with('success', 'City added successfully.');
    }

    public function addCinema(AddCinemaRequest $request)
    {
        $this->cinemaRepository->addCinema($request->city_id, $request->cinema_name, $request->cinema_address);

        return redirect()->route('cinemas')->with('success', 'Cinema added successfully.');
    }

    public function deleteCity($cityId)
    {
        $city = $this->cinemaRepository->getCityById($cityId);

        if (!$this->cinemaRepository->deleteCity($city)) {
            return redirect()->route('cinemas')->with('error', 'City cannot be deleted because it has active bookings.');
        }

        return redirect()->route('cinemas')->with('success', 'City deleted successfully.');
    }

    public function deleteCinema($cinemaId)
    {
        $cinema = $this->cinemaRepository->getCinemaById($cinemaId);

        if (!$this->cinemaRepository->deleteCinema($cinema)) {
            return redirect()->route('cinemas')->with('error', 'Cinema cannot be deleted because it has active bookings.');
        }

        return redirect()->route('cinemas')->with('success', 'Cinema deleted successfully.');
    }
}
