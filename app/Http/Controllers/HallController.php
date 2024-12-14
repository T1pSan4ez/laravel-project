<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHallRequest;
use App\Http\Requests\UpdateSeatsRequest;
use App\Repositories\HallRepositoryInterface;
use Illuminate\Http\Request;

class HallController extends Controller
{
    protected $hallRepository;

    public function __construct(HallRepositoryInterface $hallRepository)
    {
        $this->hallRepository = $hallRepository;
    }

    public function index($cinema_id)
    {
        $cinema = $this->hallRepository->getCinemaWithHalls($cinema_id);
        return view('admin.halls', [
            'cinema' => $cinema,
            'halls' => $cinema->halls,
        ]);
    }

    public function store(StoreHallRequest $request, $cinema_id)
    {
        $this->hallRepository->createHall($cinema_id, ['name' => $request->input('name')]);

        return redirect()->route('halls', ['cinema_id' => $cinema_id])
            ->with('success', 'Hall added successfully.');
    }

    public function destroy($cinema_id, $hall_id)
    {
        $deleted = $this->hallRepository->deleteHall($cinema_id, $hall_id);

        if (!$deleted) {
            return redirect()->route('halls', ['cinema_id' => $cinema_id]);
        }

        return redirect()->route('halls', ['cinema_id' => $cinema_id])
            ->with('success', 'Hall deleted successfully.');
    }

    public function clearSeats(Request $request, $cinema_id, $hall_id)
    {
        $hall = $this->hallRepository->findHall($cinema_id, $hall_id);

        if (!$this->hallRepository->clearSeats($hall)) {
            return redirect()->route('halls.edit', ['cinema_id' => $cinema_id, 'hall_id' => $hall_id])
                ->with('error', 'Cannot clear the hall. Some slots have status "booked" or "paid".');
        }

        return redirect()->route('halls.edit', ['cinema_id' => $cinema_id, 'hall_id' => $hall_id])
            ->with('success', 'Hall seats cleared successfully.');
    }

    public function edit(Request $request, $cinema_id, $hall_id)
    {
        $minRows = 1;
        $maxRows = 20;
        $minColumns = 1;
        $maxColumns = 20;

        $data = $request->only('rows', 'columns');

        $hallData = $this->hallRepository->editHall($cinema_id, $hall_id, $data);

        if ($hallData === null) {
            return redirect()->route('halls.edit', ['cinema_id' => $cinema_id, 'hall_id' => $hall_id])
                ->with('error', 'Валидация не прошла: проверьте количество рядов и колонок.');
        }

        return view('admin.halls-edit', array_merge($hallData, [
            'minRows' => $minRows,
            'maxRows' => $maxRows,
            'minColumns' => $minColumns,
            'maxColumns' => $maxColumns,
        ]));
    }

    public function updateSeats(UpdateSeatsRequest $request, $cinema_id, $hall_id)
    {
        $hall = $this->hallRepository->findHall($cinema_id, $hall_id);
        $selectedSeats = json_decode($request->input('selected_seats'), true);
        $errors = [];

        if (!$this->hallRepository->updateHallSeats($hall, $selectedSeats, $errors)) {
            return redirect()->route('halls.edit', ['cinema_id' => $cinema_id, 'hall_id' => $hall_id])
                ->with('error', 'Some slots with status "booked" or "paid" cannot be modified or deleted.')
                ->with('bookedSlots', $errors['bookedOrPaidSlots']);
        }

        return redirect()->route('halls.edit', ['cinema_id' => $cinema_id, 'hall_id' => $hall_id])
            ->with('success', 'Hall configuration updated successfully.');
    }
}
