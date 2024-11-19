<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\City;
use App\Models\Cinema;
use App\Models\Hall;
use App\Models\Movie;
use App\Models\Session;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $adminCount = User::where('role', '1')->count();
        $userCount = User::where('role', '0')->count();
        $cityCount = City::count();
        $cinemaCount = Cinema::count();
        $hallCount = Hall::count();
        $movieCount = Movie::count();
        $sessionCount = Session::count();

        return view('admin.dashboard', compact(
            'adminCount',
            'userCount',
            'cityCount',
            'cinemaCount',
            'hallCount',
            'movieCount',
            'sessionCount'
        ));
    }
}
