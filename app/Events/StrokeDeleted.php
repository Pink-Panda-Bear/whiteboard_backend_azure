<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StrokeDeleted implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $strokeId;
    public $boardId;

    /**
     * Create a new event instance.
     */
    public function __construct($strokeId, $boardId) {
        $this->strokeId = $strokeId;
        $this->boardId = $boardId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array {
        return [
            new Channel('board.' . $this->boardId), // ✅ Public kanal
        ];
    }

    public function broadcastAs() {
        return 'stroke.deleted';
    }

    public function broadcastWith() {
        return [
            'strokeId' => $this->strokeId,
        ];
    }
}