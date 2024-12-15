<?php

namespace App\Repositories;

use App\Interfaces\SessionRepositoryInterface;
use App\Models\City;
use App\Models\Movie;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SessionRepository implements SessionRepositoryInterface
{
    public function getAllMoviesPaginated(int $perPage): LengthAwarePaginator
    {
        return Movie::orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'movies_page');
    }

    public function getAllSessionsWithRelations(int $perPage): LengthAwarePaginator
    {
        return Session::with(['movie', 'hall.cinema'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'sessions_page');
    }

    public function getAllCitiesWithRelations(): Collection
    {
        return City::with('cinemas.halls')->orderBy('name', 'asc')->get();
    }

    public function createSession(array $data): Session
    {
        $startTime = Carbon::parse($data['start_time']);
        if ($startTime->isToday() || $startTime->isPast()) {
            throw new \Exception('You cannot create a session with today\'s or past dates.');
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
            throw new \Exception('There is already a session scheduled in this hall during the selected time.');
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

        return $session;
    }

    public function searchMovies(?string $query = '', int $perPage = 5): LengthAwarePaginator
    {
        if (empty($query)) {
            return Movie::orderBy('created_at', 'desc')->paginate($perPage);
        }

        return Movie::where('title', 'like', '%' . $query . '%')->paginate($perPage);
    }

    public function findSessionById(int $id): Session
    {
        return Session::findOrFail($id);
    }

    public function updateSession(Session $session, array $data): bool
    {
        $startTime = Carbon::parse($data['start_time']);
        if ($startTime->isToday() || $startTime->isPast()) {
            throw new \Exception('You cannot update a session with today\'s or past dates.');
        }

        $movie = Movie::findOrFail($data['movie_id']);
        $movieDuration = $movie->duration;

        $technicalBreak = $data['technical_break'] ?? $session->technical_break;

        $endTime = $startTime->copy()->addMinutes($movieDuration + $technicalBreak);

        $existingSession = Session::where('hall_id', $data['hall_id'])
            ->where('id', '!=', $session->id)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($query) use ($startTime, $endTime) {
                        $query->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })->first();

        if ($existingSession) {
            throw new \Exception('There is already a session scheduled in this hall during the selected time.');
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

        return true;
    }

    public function deleteSession(Session $session): bool
    {
        $hasBookedOrPaidSlots = $session->sessionSlots()->whereIn('status', ['booked', 'paid'])->exists();

        if ($hasBookedOrPaidSlots) {
            session()->flash('error', 'Cannot delete the session because it has booked or paid slots.');
            return false;
        }

        return $session->delete();
    }
}
