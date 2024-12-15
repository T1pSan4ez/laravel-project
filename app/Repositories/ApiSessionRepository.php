<?php

namespace App\Repositories;

use App\Interfaces\ApiSessionRepositoryInterface;
use App\Models\Session;

class ApiSessionRepository implements ApiSessionRepositoryInterface
{
    public function getSessionById(int $id): Session
    {
        return Session::with(['hall.cinema.city', 'hall.slots'])->findOrFail($id);
    }
}
