<?php

namespace App\Repositories;

use App\Interfaces\GoogleAuthRepositoryInterface;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthRepository implements GoogleAuthRepositoryInterface
{
    public function getRedirectUrl(): string
    {
        return Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
    }

    public function authenticateUser($googleUser): string
    {
        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'password' => bcrypt(str()->random(16)),
            ]
        );

        return $user->createToken('auth_token')->plainTextToken;
    }
}
