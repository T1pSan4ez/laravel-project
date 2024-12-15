<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface PDFRepositoryInterface
{
    public function getMoviesWithSessions(): Collection;

    public function getCitiesWithCinemas(): Collection;

    public function getFilteredPurchases(array $filters): Collection;

    public function calculateTotalEarnings(Collection $purchases): float;

    public function getMovieTitleById(int $movieId): ?string;

    public function getCityNameById(int $cityId): ?string;
}
