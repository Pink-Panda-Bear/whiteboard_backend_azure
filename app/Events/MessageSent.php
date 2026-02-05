<?php

namespace App\Events;
use App\Models\Message;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;
    public int $boardId;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message, $boardId) {
        $this->message = $message->load('user:id,name');
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

    public function broadcastAs(): string {
        return 'message.sent';
    }

    public function broadcastWith() {
        return [
            'message' => $this->message->toArray(),
        ];
    }
}
