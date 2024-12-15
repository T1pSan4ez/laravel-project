<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function getAllNonAdminUsersPaginated(int $perPage): LengthAwarePaginator
    {
        $currentUserId = auth()->id();

        return User::where(function ($query) {
            $query->where('role', '!=', 1)
            ->orWhere('user_type', '=', 'admin');
        })
            ->where('user_type', '!=', 'super_admin')
            ->where('id', '!=', $currentUserId)
            ->orderBy('name', 'asc')
            ->paginate($perPage);
    }

    public function updateUserType(User $user, string $userType): bool
    {
        $user->user_type = $userType;

        if ($userType === 'admin') {
            $user->role = 1;
        } else {
            $user->role = 0;
        }

        return $user->save();
    }
}
