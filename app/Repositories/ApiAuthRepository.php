<?php

namespace App\Repositories;

use App\Jobs\SendWelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ApiAuthRepository implements ApiAuthRepositoryInterface
{
    public function registerUser(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        dispatch(new SendWelcomeMail($user));

        return $user;
    }

    public function loginUser(array $credentials, bool $remember): ?string
    {
        if (!Auth::attempt($credentials, $remember)) {
            return null;
        }

        $user = Auth::user();

        return $user->createToken('auth_token')->plainTextToken;
    }

    public function logoutUser(Request $request): void
    {
        $request->user()->currentAccessToken()->delete();
        $request->user()->update(['remember_token' => null]);
    }
}
