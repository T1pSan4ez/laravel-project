<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserActivityRequest;
use App\Http\Resources\RecommendedMovieResource;
use App\Http\Resources\RecommendedSessionResource;
use App\Interfaces\UserActivityRepositoryInterface;
use Illuminate\Http\Request;

class UserActivityController extends Controller
{
    protected $userActivityRepository;

    public function __construct(UserActivityRepositoryInterface $userActivityRepository)
    {
        $this->userActivityRepository = $userActivityRepository;
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $userActivities = $this->userActivityRepository->getUserActivitiesMovieIds($user->id);

        if (empty($userActivities)) {
            return response()->json([
                'message' => 'Activity history is empty',
                'recommendations' => []
            ], 200);
        }

        $userMovieGenres = $this->userActivityRepository->getUserMovieGenres($userActivities);

        if (empty($userMovieGenres)) {
            return response()->json([
                'message' => 'Genres of movies are empty. Recommendations cannot be created.',
                'recommendations' => []
            ], 200);
        }

        $recommendedMovies = $this->userActivityRepository->getRecommendedMovies($userMovieGenres, $userActivities);

        return response()->json([
            'recommendations' => RecommendedMovieResource::collection($recommendedMovies),
        ], 200);
    }

    public function store(StoreUserActivityRequest $request)
    {
        $validated = $request->validated();

        $existingActivity = $this->userActivityRepository->findExistingActivity(
            $request->user()->id,
            $validated['movie_id'],
            $validated['action']
        );

        if ($existingActivity) {
            return response()->json([
                'message' => 'Activity already exists.',
                'activity' => $existingActivity,
            ], 200);
        }

        $activity = $this->userActivityRepository->createActivity([
            'user_id' => $request->user()->id,
            'movie_id' => $validated['movie_id'],
            'action' => $validated['action'],
        ]);

        return response()->json([
            'message' => 'Activity recorded successfully.',
            'activity' => $activity,
        ], 201);
    }

    public function recommendSessions(Request $request)
    {
        $user = $request->user();

        $viewedMovies = $this->userActivityRepository->getViewedMovies($user->id);

        if (empty($viewedMovies)) {
            return response()->json([
                'message' => 'You have no viewed movies. Sessions recommendations are not possible.',
                'sessions' => []
            ], 200);
        }

        $bookedMovies = $this->userActivityRepository->getBookedMovies($user->id);

        $filteredMovies = array_diff($viewedMovies, $bookedMovies);

        if (empty($filteredMovies)) {
            return response()->json([
                'message' => 'All viewed movies are already booked. Sessions recommendations are not possible.',
                'sessions' => []
            ], 200);
        }

        $recommendedSessions = $this->userActivityRepository->getRecommendedSessions($filteredMovies);

        return response()->json([
            'sessions' => RecommendedSessionResource::collection($recommendedSessions),
        ], 200);
    }
}
