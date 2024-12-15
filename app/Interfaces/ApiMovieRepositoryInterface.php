<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface ApiMovieRepositoryInterface
{
    public function getMoviesByCinema(int $cinemaId): Collection;
}
