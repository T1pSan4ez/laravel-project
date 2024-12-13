<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface ApiCinemaRepositoryInterface
{
    public function getCitiesWithSessions(): Collection;
}
