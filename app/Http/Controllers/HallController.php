<?php

namespace App\Http\Controllers;

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

    public function store(Request $request, $cinema_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

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
        $cinema = Cinema::findOrFail($cinema_id);
        $hall = Hall::where('cinema_id', $cinema_id)->findOrFail($hall_id);

        $rows = $request->input('rows', 10);
        $columns = $request->input('columns', 10);

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
        ]);
    }

    public function updateSeats(Request $request, $cinema_id, $hall_id)
    {
        $hall = Hall::where('cinema_id', $cinema_id)->findOrFail($hall_id);

        foreach ($request->input('seats', []) as $slot_id => $is_available) {
            $slot = $hall->slots()->find($slot_id);
            if ($slot) {
                $slot->is_available = $is_available;
                $slot->save();
            }
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
