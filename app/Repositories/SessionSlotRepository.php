<?php

namespace App\Repositories;

use App\Events\BookingSlots;
use App\Interfaces\SessionSlotRepositoryInterface;
use App\Jobs\DeleteBookedSlots;
use App\Models\SessionSlot;

class SessionSlotRepository implements SessionSlotRepositoryInterface
{
    public function updateSlotStatuses(int $sessionId, array $slots): array
    {
        $updatedSlots = [];

        foreach ($slots as $slotData) {
            $sessionSlot = SessionSlot::where('session_id', $sessionId)
                ->where('slot_id', $slotData['slot_id'])
                ->first();

            if ($sessionSlot) {
                $sessionSlot->status = $slotData['status'];
                $sessionSlot->save();

                if ($slotData['status'] === 'booked') {
                    DeleteBookedSlots::dispatch($sessionSlot->id, $slotData['slot_id'])->delay(now()->addMinutes(1));
                }

                $updatedSlots[] = [
                    'slot_id' => $sessionSlot->slot_id,
                    'status' => $sessionSlot->status,
                ];
            }
        }

        event(new BookingSlots($updatedSlots));

        return $updatedSlots;
    }
}
