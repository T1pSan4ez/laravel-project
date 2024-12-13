<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function getAllNonAdminUsersPaginated(int $perPage): LengthAwarePaginator
    {
        return User::where('role', '!=', 1)
            ->orderBy('name', 'asc')
            ->paginate($perPage);
    }
}
