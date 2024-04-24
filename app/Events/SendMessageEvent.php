<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class SendMessageEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;
    public int $recipient_type;
    public int $recipient_entity_id;
    /**
     * Create a new event instance.
     */
    public function __construct(Message $message, int $recipient_type, int $recipient_entity_id)
    {
        $this -> message = $message;
        $this -> recipient_type = $recipient_type;
        $this -> recipient_entity_id = $recipient_entity_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
