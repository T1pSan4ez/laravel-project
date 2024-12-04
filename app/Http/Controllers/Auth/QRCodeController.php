<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\QRToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class QRCodeController extends Controller
{
    public function generateToken(Request $request)
    {
        $user = $request->user();

        $existingToken = QRToken::where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->first();

        if ($existingToken) {
            return response()->json(['token' => $existingToken->token], 200);
        }

        $token = Str::random(32);

        QRToken::updateOrCreate(
            ['user_id' => $user->id],
            [
                'token' => $token,
                'expires_at' => now()->addMinutes(5),
            ]
        );

        return response()->json(['token' => $token], 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $qrToken = QRToken::where('token', $request->token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$qrToken) {
            return response()->json(['message' => 'Invalid or expired QR token.'], 401);
        }

        $user = $qrToken->user;

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $qrToken->delete();

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

}
