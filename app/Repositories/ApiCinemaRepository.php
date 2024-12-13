<?php

namespace App\Repositories;

use App\Models\City;
use Illuminate\Support\Collection;

class ApiCinemaRepository implements ApiCinemaRepositoryInterface
{
    public function getCitiesWithSessions(): Collection
    {
        return City::whereHas('cinemas.halls.slots.sessionSlots', function ($query) {
            $query->whereHas('session');
        })->with([
            'cinemas' => function ($cinemaQuery) {
                $cinemaQuery->whereHas('halls.slots.sessionSlots', function ($query) {
                    $query->whereHas('session');
                })->with([
                    'halls' => function ($hallQuery) {
                        $hallQuery->whereHas('slots.sessionSlots', function ($query) {
                            $query->whereHas('session');
                        })->with(['slots' => function ($slotQuery) {
                            $slotQuery->whereHas('sessionSlots', function ($query) {
                                $query->whereHas('session');
                            });
                        }]);
                    }
                ]);
            }
        ])->orderBy('name', 'asc')->get();
    }
}
