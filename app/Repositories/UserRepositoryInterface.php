<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function getAllNonAdminUsersPaginated(int $perPage): LengthAwarePaginator;
}
