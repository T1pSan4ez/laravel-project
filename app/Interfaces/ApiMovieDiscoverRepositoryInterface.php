<?php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ApiMovieDiscoverRepositoryInterface
{
    public function getMovies(array $filters): LengthAwarePaginator;
    public function getMovieById(int $id): object;
    public function getAllGenres(): Collection;
}
