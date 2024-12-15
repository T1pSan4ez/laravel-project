<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface ApiProductRepositoryInterface
{
    public function getAllProducts(): Collection;
}
