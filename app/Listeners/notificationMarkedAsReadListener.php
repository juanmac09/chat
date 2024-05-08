<?php

namespace App\Listeners;

use App\Events\markAsReadAMessageEvent;
use App\Interfaces\IMqtt;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class notificationMarkedAsReadListener
{
    public $mqtt_service;
    /**
     * Create the event listener.
     */
    public function __construct(IMqtt $mqtt_service)
    {
        $this->mqtt_service = $mqtt_service;
    }

    /**
     * Handle the event.
     */
    public function handle(markAsReadAMessageEvent $event): void
    {
        
        if ($event->type === 1) {
            $message = $event->message;
            $read_at = $message->users_reads()->select(['message_reads.read_at'])->orderByDesc('read_at')->first();

            $data = [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'reader_id' => $event->user->id,
                'reader_name' => $event->user->name,
                'read_at' => $read_at->read_at,
                'recipient_type' => $message->recipient()->value('recipient_type'),
                'recipient_entity_id' => $message->recipient()->value('recipient_entity_id'),
            ];

            $data = json_encode($data);
            $topic = 'markAsRead/user/';
            $this->mqtt_service->published($topic . $message->sender_id, $data);
        }else{
            $data = [
                'sender' => $event -> sender,
            ];
            $data = json_encode($data);
            $topic = 'markAllMessageAsRead/user/';
            $this->mqtt_service->published($topic.$event -> sender, $data);
        }

        
       
    }
}
