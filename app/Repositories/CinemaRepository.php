<?php

namespace App\Repositories;

use App\Interfaces\CinemaRepositoryInterface;
use App\Models\Cinema;
use App\Models\City;

class CinemaRepository implements CinemaRepositoryInterface
{
    public function getAllCitiesWithCinemas()
    {
        return City::with('cinemas')->orderBy('name', 'asc')->get();
    }

    public function addCity(string $name): City
    {
        return City::create(['name' => $name]);
    }

    public function addCinema(int $cityId, string $name, string $address): Cinema
    {
        return Cinema::create([
            'city_id' => $cityId,
            'name' => $name,
            'address' => $address,
        ]);
    }

    public function getCityById(int $id): City
    {
        return City::findOrFail($id);
    }

    public function getCinemaById(int $id): Cinema
    {
        return Cinema::findOrFail($id);
    }

    public function deleteCity(City $city): bool
    {
        $hasBookedOrPaidSlots = $city->cinemas()->whereHas('halls.slots.sessionSlots', function ($query) {
            $query->whereIn('status', ['booked', 'paid']);
        })->exists();

        if ($hasBookedOrPaidSlots) {
            session()->flash('error', 'Cannot delete the city because it has booked or paid seats in associated cinemas.');
            return false;
        }

        return $city->delete();
    }

    public function deleteCinema(Cinema $cinema): bool
    {
        $hasBookedOrPaidSlots = $cinema->halls()->whereHas('slots.sessionSlots', function ($query) {
            $query->whereIn('status', ['booked', 'paid']);
        })->exists();

        if ($hasBookedOrPaidSlots) {
            session()->flash('error', 'Cannot delete the cinema because it has booked or paid seats in associated halls.');
            return false;
        }

        return $cinema->delete();
    }
}
