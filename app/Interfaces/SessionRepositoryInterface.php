<?php

namespace App\Interfaces;

use App\Models\Session;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SessionRepositoryInterface
{
    public function getAllMoviesPaginated(int $perPage): LengthAwarePaginator;

    public function getAllSessionsWithRelations(int $perPage): LengthAwarePaginator;

    public function getAllCitiesWithRelations(): Collection;

    public function createSession(array $data): Session;

    public function searchMovies(string $query, int $perPage): LengthAwarePaginator;

    public function findSessionById(int $id): Session;

    public function updateSession(Session $session, array $data): bool;

    public function deleteSession(Session $session): bool;
}
