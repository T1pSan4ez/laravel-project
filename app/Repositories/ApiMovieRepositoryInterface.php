<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface ApiMovieRepositoryInterface
{
    public function getMoviesByCinema(int $cinemaId): Collection;
}
