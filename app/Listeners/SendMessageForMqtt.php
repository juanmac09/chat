<?php

namespace App\Listeners;

use App\Events\SendMessageEvent;
use App\Interfaces\IMqtt;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendMessageForMqtt
{

    public $mqtt_service;
    /**
     * Create the event listener.
     */
    public function __construct(IMqtt $mqtt_service)
    {
        $this ->mqtt_service = $mqtt_service;
    }

    /**
     * Handle the event.
     */
    public function handle(SendMessageEvent $event): void
    {
        $data = [
            'id' => $event -> message -> id,
            'content' => $event -> message -> content,
            'sender_id' => $event -> message -> sender_id,
            'recipient_type' => $event -> recipient_type,
            'recipient_entity_id' => $event -> recipient_entity_id,
            'created_at' => $event -> message -> created_at,
        ];

        $data = json_encode($data);
        $topic = ($event -> recipient_type == 1) ? 'user/' : 'group/';
        $this -> mqtt_service -> published($topic.$event -> recipient_entity_id, $data);   
    }
}
