<?php

namespace App\Events;

use App\Models\Stroke;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StrokeUpdated implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $stroke;
    public $boardId;

    /**
     * Create a new event instance.
     */
    public function __construct(Stroke $stroke, $boardId) {
        $this->stroke = $stroke;
        $this->boardId = $boardId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array {
        return [
            new Channel('board.' . $this->boardId),
        ];
    }

    public function broadcastAs() {
        return 'stroke.updated';
    }

    public function broadcastWith() {
        return [
            'stroke' => $this->stroke,
        ];
    }
}