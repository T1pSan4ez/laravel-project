<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

interface ApiUserRepositoryInterface
{
    public function getAuthenticatedUser(): User;

    public function updateProfile(User $user, array $data): bool;

    public function updatePassword(User $user, string $password): bool;

    public function getUserPurchases(User $user): Collection;

    public function updateAvatar(User $user, UploadedFile $file): bool;
}
