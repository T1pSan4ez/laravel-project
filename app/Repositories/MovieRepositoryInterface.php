<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\Paginator;

interface MovieRepositoryInterface
{
    public function getAllMovies(): Paginator;

    public function getMovieGenres();

    public function createMovie(array $data, array $genres): mixed;

    public function updateMovie(int $id, array $data, array $genres): bool;

    public function deleteMovie(int $id): bool;

    public function searchMovies(string $query): Paginator;
}
