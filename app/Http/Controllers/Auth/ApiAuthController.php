<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Repositories\ApiAuthRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    protected ApiAuthRepositoryInterface $authRepository;

    public function __construct(ApiAuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authRepository->registerUser($request->validated());
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    public function login(LoginRequest $request)
    {
        $token = $this->authRepository->loginUser(
            $request->only('email', 'password'),
            $request->has('remember')
        );

        if (!$token) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'token' => $token,
            'user' => Auth::user(),
        ]);
    }

    public function logout(Request $request)
    {
        $this->authRepository->logoutUser($request);

        return response()->json([
            'message' => 'Successfully logged out',
        ])->withCookie(cookie()->forget('remember_web', '/', '.example.camelot'));
    }
}
