<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Interfaces\GoogleAuthRepositoryInterface;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    protected $googleAuthRepository;

    public function __construct(GoogleAuthRepositoryInterface $googleAuthRepository)
    {
        $this->googleAuthRepository = $googleAuthRepository;
    }

    public function redirectToGoogle()
    {
        $redirectUrl = $this->googleAuthRepository->getRedirectUrl();

        return response()->json(['url' => $redirectUrl]);
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $token = $this->googleAuthRepository->authenticateUser($googleUser);

            return redirect("http://localhost:5174/auth?token={$token}");
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error authenticating with Google.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
