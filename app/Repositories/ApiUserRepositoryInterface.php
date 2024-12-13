<?php

namespace App\Repositories;

use App\Models\User;

interface ApiUserRepositoryInterface
{
    public function getAuthenticatedUser(): User;

    public function updateProfile(User $user, array $data): bool;

    public function updatePassword(User $user, string $password): bool;
}
