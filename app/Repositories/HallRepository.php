<?php

namespace App\Repositories;

use App\Models\Hall;
use App\Models\Slot;
use App\Models\Cinema;
use Illuminate\Support\Collection;

class HallRepository implements HallRepositoryInterface
{
    public function getCinemaWithHalls(int $cinemaId)
    {
        return Cinema::with('halls')->findOrFail($cinemaId);
    }

    public function createHall(int $cinemaId, array $data): Hall
    {
        $cinema = Cinema::findOrFail($cinemaId);
        return $cinema->halls()->create($data);
    }

    public function findHall(int $cinemaId, int $hallId): Hall
    {
        return Hall::where('cinema_id', $cinemaId)->findOrFail($hallId);
    }

    public function deleteHall(int $cinemaId, int $hallId): bool
    {
        $hall = $this->findHall($cinemaId, $hallId);
        return $hall->delete();
    }

    public function clearSeats(Hall $hall): bool
    {
        $protectedSlots = $hall->slots()->whereHas('sessionSlots', function ($query) {
            $query->whereIn('status', ['booked', 'paid']);
        })->exists();

        if ($protectedSlots) {
            return false;
        }

        $hall->slots()->delete();

        $hall->sessions()->each(function ($session) {
            $session->sessionSlots()->delete();
        });

        return true;
    }

    public function editHall(int $cinemaId, int $hallId, array $data): array
    {
        $cinema = Cinema::findOrFail($cinemaId);
        $hall = Hall::where('cinema_id', $cinemaId)->findOrFail($hallId);

        $selectedSeats = $hall->slots()->get(['row', 'number', 'type'])->toArray();

        $seatStyles = [];
        foreach ($selectedSeats as $seat) {
            $key = "{$seat['row']}-{$seat['number']}";
            $seatStyles[$key] = $seat['type'] === 'vip' ? 'vip' : 'standard';
        }

        $maxRow = !empty($selectedSeats) ? max(array_column($selectedSeats, 'row')) : 10;
        $maxColumn = !empty($selectedSeats) ? max(array_column($selectedSeats, 'number')) : 10;

        $rows = max($data['rows'] ?? 10, $maxRow);
        $columns = max($data['columns'] ?? 10, $maxColumn);

        $seats = $hall->slots;
        if ($seats->isEmpty()) {
            $virtualSeats = [];
            for ($row = 1; $row <= $rows; $row++) {
                for ($number = 1; $number <= $columns; $number++) {
                    $virtualSeats[] = (object)[
                        'row' => $row,
                        'number' => $number,
                    ];
                }
            }
            $seats = collect($virtualSeats);
        }

        return [
            'cinema' => $cinema,
            'hall' => $hall,
            'seats' => $seats,
            'rows' => $rows,
            'columns' => $columns,
            'selectedSeats' => $selectedSeats,
            'seatStyles' => $seatStyles,
        ];
    }

    public function updateHallSeats(Hall $hall, array $selectedSeats, array &$errors): bool
    {
        $existingSlots = $hall->slots()->get();

        $bookedOrPaidSlots = [];
        $slotsToDelete = [];
        $seatsToAdd = [];

        foreach ($existingSlots as $slot) {
            $isInSelected = collect($selectedSeats)->first(function ($seat) use ($slot) {
                return $seat['row'] == $slot->row && $seat['number'] == $slot->number;
            });

            if (!$isInSelected) {
                if ($slot->sessionSlots()->whereIn('status', ['booked', 'paid'])->exists()) {
                    $bookedOrPaidSlots[] = [
                        'id' => $slot->id,
                        'row' => $slot->row,
                        'number' => $slot->number,
                        'status' => $slot->sessionSlots->first()->status,
                    ];
                } else {
                    $slotsToDelete[] = $slot->id;
                }
            }
        }

        if (!empty($bookedOrPaidSlots)) {
            $errors['bookedOrPaidSlots'] = $bookedOrPaidSlots;
            return false;
        }

        foreach ($selectedSeats as $seat) {
            $exists = $existingSlots->first(function ($slot) use ($seat) {
                return $slot->row == $seat['row'] && $slot->number == $seat['number'];
            });

            if (!$exists) {
                $seatsToAdd[] = [
                    'hall_id' => $hall->id,
                    'row' => $seat['row'],
                    'number' => $seat['number'],
                    'type' => $seat['type'],
                    'price' => $seat['price'],
                ];
            }
        }

        if (!empty($slotsToDelete)) {
            Slot::whereIn('id', $slotsToDelete)->each(function ($slot) {
                $slot->sessionSlots()->delete();
                $slot->delete();
            });
        }

        if (!empty($seatsToAdd)) {
            $hall->slots()->insert($seatsToAdd);

            $newSlots = $hall->slots()->whereIn('row', array_column($seatsToAdd, 'row'))
                ->whereIn('number', array_column($seatsToAdd, 'number'))
                ->get();

            foreach ($newSlots as $slot) {
                foreach ($hall->sessions as $session) {
                    $session->sessionSlots()->create([
                        'slot_id' => $slot->id,
                        'status' => 'available',
                    ]);
                }
            }
        }

        return true;
    }
}
