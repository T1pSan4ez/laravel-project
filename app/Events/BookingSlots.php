<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingSlots implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $updatedSlots;

    /**
     * Create a new event instance.
     */
    public function __construct(array $updatedSlots)
    {
        $this->updatedSlots = $updatedSlots;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('booking_slots'),
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */

    public function broadcastAs(): string
    {
        return 'booking_slots';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith()
    {
        return [
            'updatedSlots' => $this->updatedSlots,
        ];
    }
}
