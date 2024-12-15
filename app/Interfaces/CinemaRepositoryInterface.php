<?php

namespace App\Interfaces;

use App\Models\Cinema;
use App\Models\City;

interface CinemaRepositoryInterface
{
    public function getAllCitiesWithCinemas();

    public function addCity(string $name): City;

    public function addCinema(int $cityId, string $name, string $address): Cinema;

    public function deleteCity(City $city): bool;

    public function deleteCinema(Cinema $cinema): bool;
}
