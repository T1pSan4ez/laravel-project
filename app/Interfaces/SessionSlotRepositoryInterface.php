<?php

namespace App\Interfaces;

interface SessionSlotRepositoryInterface
{
    public function updateSlotStatuses(int $sessionId, array $slots): array;
}
