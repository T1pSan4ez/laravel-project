<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        $redirectUrl = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();

        return response()->json(['url' => $redirectUrl]);
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'password' => bcrypt(str()->random(16)),
                ]
            );

            $token = $user->createToken('auth_token')->plainTextToken;

            return redirect("http://localhost:5174/auth?token={$token}");
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error authenticating with Google.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
