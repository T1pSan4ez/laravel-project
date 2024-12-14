<?php

namespace App\Http\Controllers;

use App\Http\Requests\SessionRequest;
use App\Repositories\SessionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SessionController extends Controller
{
    protected $sessionRepository;

    public function __construct(SessionRepositoryInterface $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    public function index()
    {
        $movies = $this->sessionRepository->getAllMoviesPaginated(5);
        $sessions = $this->sessionRepository->getAllSessionsWithRelations(5);
        $cities = $this->sessionRepository->getAllCitiesWithRelations();

        return view('admin.sessions', [
            'movies' => $movies,
            'sessions' => $sessions,
            'cities' => $cities,
        ]);
    }

    public function store(SessionRequest $request)
    {
        try {
            $this->sessionRepository->createSession($request->validated());
            return redirect()->route('sessions')->with('success', 'Session created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['start_time' => $e->getMessage()])->withInput();
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('query', '');
        $movies = $this->sessionRepository->searchMovies($query, 5);
        $cities = $this->sessionRepository->getAllCitiesWithRelations();
        $sessions = $this->sessionRepository->getAllSessionsWithRelations(5);

        return view('admin.sessions', compact('movies', 'query', 'cities', 'sessions'));
    }

    public function edit($id)
    {
        $session = $this->sessionRepository->findSessionById($id);

        $session->start_time = Carbon::parse($session->start_time);

        $movies = $this->sessionRepository->getAllMoviesPaginated(100);
        $cities = $this->sessionRepository->getAllCitiesWithRelations();

        return view('admin.sessions-edit', compact('session', 'movies', 'cities'));
    }

    public function update(SessionRequest $request, $id)
    {
        try {
            $session = $this->sessionRepository->findSessionById($id);
            $this->sessionRepository->updateSession($session, $request->validated());
            return redirect()->route('sessions')->with('success', 'Session updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['start_time' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $session = $this->sessionRepository->findSessionById($id);

        if (!$this->sessionRepository->deleteSession($session)) {
            return redirect()->route('sessions')->with('error', 'Cannot delete session with booked or paid slots.');
        }

        return redirect()->route('sessions')->with('success', 'Session deleted successfully.');
    }
}
