<?php

namespace App\Http\Controllers;

use App\Interfaces\DashboardRepositoryInterface;

class DashboardController extends Controller
{
    protected $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function index()
    {
        $adminCount = $this->dashboardRepository->getAdminCount();
        $userCount = $this->dashboardRepository->getUserCount();
        $cityCount = $this->dashboardRepository->getCityCount();
        $cinemaCount = $this->dashboardRepository->getCinemaCount();
        $hallCount = $this->dashboardRepository->getHallCount();
        $movieCount = $this->dashboardRepository->getMovieCount();
        $sessionCount = $this->dashboardRepository->getSessionCount();

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
