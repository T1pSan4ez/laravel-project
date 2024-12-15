<?php

namespace App\Interfaces;

use App\Models\QRToken;
use App\Models\User;

interface QRCodeRepositoryInterface
{
    public function generateToken(User $user): string;
    public function findValidToken(string $token): ?QRToken;
    public function deleteToken(QRToken $qrToken): void;
}
