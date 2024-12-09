<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SlotStatusUpdated implements ShouldBroadcast
{
    use  InteractsWithSockets, SerializesModels;

    public $slotId;
    public $status;

    public function __construct($slotId, $status)
    {
        $this->slotId = $slotId;
        $this->status = $status;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('slot_status'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'slot_status.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'slot_id' => $this->slotId,
            'status' => $this->status,
        ];
    }
}
