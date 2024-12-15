<?php

namespace App\Repositories;

use App\Interfaces\QRCodeRepositoryInterface;
use App\Models\QRToken;
use App\Models\User;
use Illuminate\Support\Str;

class QRCodeRepository implements QRCodeRepositoryInterface
{
    public function generateToken(User $user): string
    {
        $existingToken = QRToken::where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->first();

        if ($existingToken) {
            return $existingToken->token;
        }

        $token = Str::random(32);

        QRToken::updateOrCreate(
            ['user_id' => $user->id],
            [
                'token' => $token,
                'expires_at' => now()->addMinutes(5),
            ]
        );

        return $token;
    }

    public function findValidToken(string $token): ?QRToken
    {
        return QRToken::where('token', $token)
            ->where('expires_at', '>', now())
            ->first();
    }

    public function deleteToken(QRToken $qrToken): void
    {
        $qrToken->delete();
    }
}
