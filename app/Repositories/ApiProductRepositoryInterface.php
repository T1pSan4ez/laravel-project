<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface ApiProductRepositoryInterface
{
    public function getAllProducts(): Collection;
}
