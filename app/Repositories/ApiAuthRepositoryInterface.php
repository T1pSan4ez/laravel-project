<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;

interface ApiAuthRepositoryInterface
{
    public function registerUser(array $data): User;
    public function loginUser(array $credentials, bool $remember): ?string;
    public function logoutUser(Request $request): void;
}
