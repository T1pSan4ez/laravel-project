<?php

namespace App\Repositories;

use App\Interfaces\ApiCinemaRepositoryInterface;
use App\Models\City;
use Illuminate\Support\Collection;

class ApiCinemaRepository implements ApiCinemaRepositoryInterface
{
    public function getCitiesWithSessions(): Collection
    {
        $today = now()->toDateString();

        return City::whereHas('cinemas.halls.slots.sessionSlots', function ($query) use ($today) {
            $query->whereHas('session', function ($sessionQuery) use ($today) {
                $sessionQuery->whereDate('start_time', '>=', $today)
                    ->orWhereDate('start_time', $today);
            });
        })->with([
            'cinemas' => function ($cinemaQuery) use ($today) {
                $cinemaQuery->whereHas('halls.slots.sessionSlots', function ($query) use ($today) {
                    $query->whereHas('session', function ($sessionQuery) use ($today) {
                        $sessionQuery->whereDate('start_time', '>=', $today)
                            ->orWhereDate('start_time', $today);
                    });
                })->with([
                    'halls' => function ($hallQuery) use ($today) {
                        $hallQuery->whereHas('slots.sessionSlots', function ($query) use ($today) {
                            $query->whereHas('session', function ($sessionQuery) use ($today) {
                                $sessionQuery->whereDate('start_time', '>=', $today)
                                    ->orWhereDate('start_time', $today);
                            });
                        })->with(['slots' => function ($slotQuery) use ($today) {
                            $slotQuery->whereHas('sessionSlots', function ($query) use ($today) {
                                $query->whereHas('session', function ($sessionQuery) use ($today) {
                                    $sessionQuery->whereDate('start_time', '>=', $today)
                                        ->orWhereDate('start_time', $today);
                                });
                            });
                        }]);
                    }
                ]);
            }
        ])->orderBy('name', 'asc')->get();
    }

}
