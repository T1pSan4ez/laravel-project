<?php

namespace App\Http\Controllers\Api;

use App\Events\BookingSlots;
use App\Events\SlotStatusUpdated;
use App\Http\Controllers\Controller;
use App\Jobs\DeleteBookedSlots;
use App\Models\SessionSlot;
use Illuminate\Http\Request;

class SessionSlotController extends Controller
{
    public function updateStatuses($session_id, Request $request)
    {
        $validated = $request->validate([
            'slots' => 'required|array',
            'slots.*.slot_id' => 'required|integer|exists:slots,id',
            'slots.*.status' => 'required|string|in:available,booked',
        ]);

        $updatedSlots = [];

        foreach ($validated['slots'] as $slotData) {
            $sessionSlot = SessionSlot::where('session_id', $session_id)
                ->where('slot_id', $slotData['slot_id'])
                ->first();

            if ($sessionSlot) {
                $sessionSlot->status = $slotData['status'];
                $sessionSlot->save();

                if ($slotData['status'] === 'booked') {
                  DeleteBookedSlots::dispatch($sessionSlot->id)->delay(now()->addMinutes(1));
                }

                $updatedSlots[] = [
                    'slot_id' => $sessionSlot->slot_id,
                    'status' => $sessionSlot->status,
                ];
            }
        }

        event(new BookingSlots($updatedSlots));

        return response()->json([
            'message' => 'Statuses updated successfully',
            'data' => $updatedSlots,
        ]);
    }
}
