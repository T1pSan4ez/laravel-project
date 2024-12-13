<?php

namespace App\Repositories;

use App\Models\Session;

interface ApiSessionRepositoryInterface
{
    public function getSessionById(int $id): Session;
}
