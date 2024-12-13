<?php

namespace App\Repositories;

interface SessionSlotRepositoryInterface
{
    public function updateSlotStatuses(int $sessionId, array $slots): array;
}
