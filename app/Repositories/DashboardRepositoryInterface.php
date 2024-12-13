<?php

namespace App\Repositories;

interface DashboardRepositoryInterface
{
    public function getAdminCount(): int;
    public function getUserCount(): int;
    public function getCityCount(): int;
    public function getCinemaCount(): int;
    public function getHallCount(): int;
    public function getMovieCount(): int;
    public function getSessionCount(): int;
}
