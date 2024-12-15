<?php

namespace App\Interfaces;

use App\Models\Session;

interface ApiSessionRepositoryInterface
{
    public function getSessionById(int $id): Session;
}
