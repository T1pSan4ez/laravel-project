<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\QRLoginRequest;
use App\Repositories\QRCodeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QRCodeController extends Controller
{
    protected $qrCodeRepository;

    public function __construct(QRCodeRepositoryInterface $qrCodeRepository)
    {
        $this->qrCodeRepository = $qrCodeRepository;
    }

    public function generateToken(Request $request)
    {
        $user = $request->user();

        $token = $this->qrCodeRepository->generateToken($user);

        return response()->json(['token' => $token], 200);
    }

    public function login(QRLoginRequest $request)
    {
        $qrToken = $this->qrCodeRepository->findValidToken($request->token);

        if (!$qrToken) {
            return response()->json(['message' => 'Invalid or expired QR token.'], 401);
        }

        $user = $qrToken->user;

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $this->qrCodeRepository->deleteToken($qrToken);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ], 200);
    }
}
