<?php

namespace App\Jobs;

use App\Events\SlotStatusUpdated;
use App\Models\SessionSlot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteBookedSlots implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $sessionSlotId;
    /**
     * Create a new job instance.
     */
    public function __construct($sessionSlotId)
    {
        $this->sessionSlotId = $sessionSlotId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $slot = SessionSlot::find($this->sessionSlotId);

        if ($slot && $slot->status === 'booked') {
            $slot->status = 'available';
            $slot->save();

        }
    }
}