<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHallRequest;
use App\Http\Requests\UpdateSeatsRequest;
use App\Models\Cinema;
use App\Models\Hall;
use App\Models\Slot;
use Illuminate\Http\Request;

class HallController extends Controller
{
    public function index($cinema_id)
    {
        $cinema = Cinema::with('halls')->findOrFail($cinema_id);

        return view('admin.halls', [
            'cinema' => $cinema,
            'halls' => $cinema->halls,
        ]);
    }

    public function store(StoreHallRequest $request, $cinema_id)
    {
        $cinema = Cinema::findOrFail($cinema_id);
        $cinema->halls()->create([
            'name' => $request->input('name'),
        ]);

        return redirect()->route('halls', ['cinema_id' => $cinema_id])
            ->with('success', 'Hall added successfully.');
    }

    public function destroy($cinema_id, $hall_id)
    {
        $hall = Hall::where('cinema_id', $cinema_id)->findOrFail($hall_id);
        $hall->delete();

        return redirect()->route('halls', ['cinema_id' => $cinema_id])
            ->with('success', 'Hall deleted successfully.');
    }

    public function edit(Request $request, $cinema_id, $hall_id)
    {
        $minRows = 1;
        $maxRows = 20;
        $minColumns = 1;
        $maxColumns = 20;

        $cinema = Cinema::findOrFail($cinema_id);
        $hall = Hall::where('cinema_id', $cinema_id)->findOrFail($hall_id);

        $selectedSeats = $hall->slots()->get(['row', 'number', 'type'])->toArray();

        $seatStyles = [];
        foreach ($selectedSeats as $seat) {
            $key = "{$seat['row']}-{$seat['number']}";
            $seatStyles[$key] = $seat['type'] === 'vip' ? 'vip' : 'standard';
        }

        $maxRow = !empty($selectedSeats) ? max(array_column($selectedSeats, 'row')) : 10;
        $maxColumn = !empty($selectedSeats) ? max(array_column($selectedSeats, 'number')) : 10;

        $rows = max($request->input('rows', 10), $maxRow);
        $columns = max($request->input('columns', 10), $maxColumn);

        $validatedData = $request->validate([
            'rows' => "nullable|integer|min:$minRows|max:$maxRows",
            'columns' => "nullable|integer|min:$minColumns|max:$maxColumns",
        ]);

        $seats = $hall->slots;
        if ($seats->isEmpty()) {
            $virtualSeats = [];
            for ($row = 1; $row <= $rows; $row++) {
                for ($number = 1; $number <= $columns; $number++) {
                    $virtualSeats[] = (object) [
                        'row' => $row,
                        'number' => $number,
                        'is_available' => true,
                    ];
                }
            }
            $seats = collect($virtualSeats);
        }

        return view('admin.halls-edit', [
            'cinema' => $cinema,
            'hall' => $hall,
            'seats' => $seats,
            'rows' => $rows,
            'columns' => $columns,
            'selectedSeats' => $selectedSeats,
            'seatStyles' => $seatStyles,
            'minRows' => $minRows,
            'maxRows' => $maxRows,
            'minColumns' => $minColumns,
            'maxColumns' => $maxColumns,
        ]);
    }

    public function updateSeats(UpdateSeatsRequest $request, $cinema_id, $hall_id)
    {
        $hall = Hall::where('cinema_id', $cinema_id)->findOrFail($hall_id);
        $selectedSeats = json_decode($request->input('selected_seats'), true);

        $existingSeats = $hall->slots()->get(['row', 'number', 'type', 'price'])->toArray();
        $seatsToAdd = [];

        foreach ($selectedSeats as $seat) {
            $exists = collect($existingSeats)->firstWhere(function ($existingSeat) use ($seat) {
                return $existingSeat['row'] == $seat['row'] && $existingSeat['number'] == $seat['number'];
            });

            if (!$exists) {
                $seatsToAdd[] = [
                    'hall_id' => $hall_id,
                    'row' => $seat['row'],
                    'number' => $seat['number'],
                    'type' => $seat['type'],
                    'price' => $seat['price'],
                    'is_available' => true,
                ];
            }
        }

        foreach ($existingSeats as $existingSeat) {
            $found = collect($selectedSeats)->firstWhere(function ($seat) use ($existingSeat) {
                return $seat['row'] == $existingSeat['row'] && $seat['number'] == $existingSeat['number'];
            });

            if (!$found) {
                $hall->slots()
                    ->where('row', $existingSeat['row'])
                    ->where('number', $existingSeat['number'])
                    ->delete();
            }
        }

        if (!empty($seatsToAdd)) {
            $hall->slots()->insert($seatsToAdd);
        }

        return redirect()->route('halls.edit', ['cinema_id' => $cinema_id, 'hall_id' => $hall_id])
            ->with('success', 'Hall configuration updated successfully.');
    }

    public function clearSeats($cinema_id, $hall_id)
    {
        $hall = Hall::where('cinema_id', $cinema_id)->findOrFail($hall_id);

        $hall->slots()->delete();

        return redirect()->route('halls.edit', ['cinema_id' => $cinema_id, 'hall_id' => $hall_id])
            ->with('success', 'Hall seats cleared successfully.');
    }
}
