<?php

namespace App\Repositories;

use App\Interfaces\PDFRepositoryInterface;
use App\Models\City;
use App\Models\Movie;
use App\Models\Purchase;
use Illuminate\Support\Collection;

class PDFRepository implements PDFRepositoryInterface
{
    public function getMoviesWithSessions(): Collection
    {
        return Movie::whereHas('sessions')->pluck('title', 'id');
    }

    public function getCitiesWithCinemas(): Collection
    {
        return City::whereHas('cinemas.halls.sessions')->orderBy('name')->pluck('name', 'id');
    }

    public function getFilteredPurchases(array $filters): Collection
    {
        $query = Purchase::query()->with('items.sessionSlot.session.movie');

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
        }

        if (isset($filters['is_user'])) {
            if ($filters['is_user'] === 'user') {
                $query->whereNotNull('user_id');
            } elseif ($filters['is_user'] === 'guest') {
                $query->whereNull('user_id');
            }
        }

        if (isset($filters['movie_id'])) {
            $query->whereHas('items.sessionSlot.session.movie', function ($movieQuery) use ($filters) {
                $movieQuery->where('id', $filters['movie_id']);
            });
        }

        if (isset($filters['city_id'])) {
            $query->whereHas('items.sessionSlot.session.hall.cinema.city', function ($cityQuery) use ($filters) {
                $cityQuery->where('id', $filters['city_id']);
            });
        }

        return $query->get();
    }

    public function calculateTotalEarnings(Collection $purchases): float
    {
        return $purchases->flatMap->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }

    public function getMovieTitleById(int $movieId): ?string
    {
        return Movie::find($movieId)?->title;
    }

    public function getCityNameById(int $cityId): ?string
    {
        return City::find($cityId)?->name;
    }
}
