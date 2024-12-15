<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface ApiCinemaRepositoryInterface
{
    public function getCitiesWithSessions(): Collection;
}
