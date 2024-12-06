<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecommendedMovieResource;
use App\Http\Resources\RecommendedSessionResource;
use App\Models\Session;
use App\Models\UserActivity;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserActivityController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $userActivities = UserActivity::where('user_id', $user->id)
            ->pluck('movie_id')
            ->toArray();

        if (empty($userActivities)) {
            return response()->json([
                'message' => 'Активность отсутствует. Рекомендации не могут быть созданы.',
                'recommendations' => []
            ], 200);
        }

        $userMovieGenres = Movie::whereIn('id', $userActivities)
            ->with('genres')
            ->get()
            ->pluck('genres')
            ->flatten()
            ->unique('id')
            ->pluck('id')
            ->toArray();

        if (empty($userMovieGenres)) {
            return response()->json([
                'message' => 'Жанры фильмов не найдены. Рекомендации не могут быть созданы.',
                'recommendations' => []
            ], 200);
        }

        $recommendedMovies = Movie::whereHas('genres', function ($query) use ($userMovieGenres) {
            $query->whereIn('genres.id', $userMovieGenres);
        })
            ->whereNotIn('id', $userActivities)
            ->with('genres')
            ->distinct()
            ->limit(6)
            ->get();

        return response()->json([
            'recommendations' => RecommendedMovieResource::collection($recommendedMovies),
        ], 200);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'action' => 'required|in:view,booking',
        ]);

        $existingActivity = UserActivity::where('user_id', $request->user()->id)
            ->where('movie_id', $validated['movie_id'])
            ->where('action', $validated['action'])
            ->first();

        if ($existingActivity) {
            return response()->json([
                'message' => 'Активность уже существует.',
                'activity' => $existingActivity,
            ], 200);
        }

        $activity = UserActivity::create([
            'user_id' => $request->user()->id,
            'movie_id' => $validated['movie_id'],
            'action' => $validated['action'],
        ]);

        return response()->json([
            'message' => 'Активность успешно записана.',
            'activity' => $activity,
        ], 201);
    }

    public function recommendSessions(Request $request)
    {
        $user = $request->user();

        $viewedMovies = UserActivity::where('user_id', $user->id)
            ->where('action', 'view')
            ->pluck('movie_id')
            ->toArray();

        if (empty($viewedMovies)) {
            return response()->json([
                'message' => 'У вас нет просмотренных фильмов. Рекомендации сеансов невозможны.',
                'sessions' => []
            ], 200);
        }

        $bookedMovies = UserActivity::where('user_id', $user->id)
            ->where('action', 'booking')
            ->pluck('movie_id')
            ->toArray();

        $filteredMovies = array_diff($viewedMovies, $bookedMovies);

        if (empty($filteredMovies)) {
            return response()->json([
                'message' => 'Все просмотренные фильмы уже забронированы. Рекомендации отсутствуют.',
                'sessions' => []
            ], 200);
        }

        $recommendedSessions = Session::with(['hall.cinema.city', 'movie'])
            ->whereIn('movie_id', $filteredMovies)
            ->where('start_time', '>=', now())
            ->get();

        return response()->json([
            'sessions' => RecommendedSessionResource::collection($recommendedSessions),
        ], 200);
    }



}
