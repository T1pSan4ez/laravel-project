<?php

namespace App\Repositories;

use App\Interfaces\DashboardRepositoryInterface;
use App\Models\Cinema;
use App\Models\City;
use App\Models\Hall;
use App\Models\Movie;
use App\Models\Session;
use App\Models\User;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getAdminCount(): int
    {
        return User::where('role', '1')->count();
    }

    public function getUserCount(): int
    {
        return User::where('role', '0')->count();
    }

    public function getCityCount(): int
    {
        return City::count();
    }

    public function getCinemaCount(): int
    {
        return Cinema::count();
    }

    public function getHallCount(): int
    {
        return Hall::count();
    }

    public function getMovieCount(): int
    {
        return Movie::count();
    }

    public function getSessionCount(): int
    {
        return Session::count();
    }
}
