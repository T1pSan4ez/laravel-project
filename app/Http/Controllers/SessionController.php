<?php

namespace App\Http\Controllers;

use App\Http\Requests\SessionRequest;
use App\Models\City;
use App\Models\Movie;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SessionController extends Controller
{
    public function index()
    {
        $movies = Movie::orderBy('created_at', 'desc')->paginate(5, ['*'], 'movies_page');

        $sessions = Session::with(['movie', 'hall.cinema'])
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'sessions_page');

        $cities = City::with('cinemas.halls')->orderBy('name', 'asc')->get();

        return view('admin.sessions', [
            'movies' => $movies,
            'sessions' => $sessions,
            'cities' => $cities,
        ]);
    }

    public function store(SessionRequest $request)
    {
        $data = $request->validated();

        $startTime = Carbon::parse($data['start_time']);
        if ($startTime->isToday() || $startTime->isPast()) {
            return redirect()->back()->withErrors(['start_time' => 'You cannot create a session with today\'s or past dates.'])->withInput();
        }

        $movie = Movie::findOrFail($data['movie_id']);
        $movieDuration = $movie->duration;

        $technicalBreak = $data['technical_break'] ?? 10;

        $endTime = $startTime->copy()->addMinutes($movieDuration + $technicalBreak);

        $existingSession = Session::where('hall_id', $data['hall_id'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($query) use ($startTime, $endTime) {
                        $query->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })->first();

        if ($existingSession) {
            return redirect()->back()->withErrors(['start_time' => 'There is already a session scheduled in this hall during the selected time.'])->withInput();
        }

        $session = Session::create([
            'movie_id' => $data['movie_id'],
            'hall_id' => $data['hall_id'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'technical_break' => $technicalBreak,
        ]);

        $slots = $session->hall->slots;
        foreach ($slots as $slot) {
            $session->sessionSlots()->create([
                'slot_id' => $slot->id,
                'status' => 'available',
            ]);
        }

        return redirect()->route('sessions')->with('success', 'Session created successfully.');
    }


    public function search(Request $request)
    {
        $query = $request->input('query');

        $movies = Movie::where('title', 'like', '%' . $query . '%')->paginate(5);
        $cities = City::with('cinemas.halls')->orderBy('name', 'asc')->get();

        return view('admin.sessions', compact('movies', 'query', 'cities'));
    }

    public function edit($id)
    {
        $session = Session::findOrFail($id);
        $session->start_time = Carbon::parse($session->start_time);

        $movies = Movie::all();
        $cities = City::with('cinemas.halls')->get();

        return view('admin.sessions-edit', compact('session', 'movies', 'cities'));
    }

    public function update(SessionRequest $request, $id)
    {
        $data = $request->validated();
        $session = Session::findOrFail($id);

        $startTime = Carbon::parse($data['start_time']);
        if ($startTime->isToday() || $startTime->isPast()) {
            return redirect()->back()->withErrors(['start_time' => 'You cannot update a session with today\'s or past dates.'])->withInput();
        }

        $movie = Movie::findOrFail($data['movie_id']);
        $movieDuration = $movie->duration;

        $technicalBreak = $data['technical_break'] ?? $session->technical_break;

        $endTime = $startTime->copy()->addMinutes($movieDuration + $technicalBreak);

        $existingSession = Session::where('hall_id', $data['hall_id'])
            ->where('id', '!=', $id)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($query) use ($startTime, $endTime) {
                        $query->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })->first();

        if ($existingSession) {
            return redirect()->back()->withErrors(['start_time' => 'There is already a session scheduled in this hall during the selected time.'])->withInput();
        }

        $hallChanged = $data['hall_id'] !== $session->hall_id;

        $session->update([
            'movie_id' => $data['movie_id'],
            'hall_id' => $data['hall_id'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'technical_break' => $technicalBreak,
        ]);

        if ($hallChanged) {
            $existingSlotIds = $session->sessionSlots->pluck('slot_id')->toArray();

            $allHallSlots = $session->hall->slots;
            foreach ($allHallSlots as $slot) {
                if (!in_array($slot->id, $existingSlotIds)) {
                    $session->sessionSlots()->create([
                        'slot_id' => $slot->id,
                        'status' => 'available',
                    ]);
                }
            }
        }

        return redirect()->route('sessions')->with('success', 'Session updated successfully.');
    }

    public function destroy($id)
    {
        $session = Session::findOrFail($id);
        $session->delete();

        return redirect()->route('sessions')->with('success', 'Session deleted successfully.');
    }
}
