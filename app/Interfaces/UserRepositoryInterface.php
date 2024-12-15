<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{    public function getAllNonAdminUsersPaginated(int $perPage): LengthAwarePaginator;
    public function updateUserType(User $user, string $userType): bool;
}
