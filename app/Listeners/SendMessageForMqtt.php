<?php

namespace App\Listeners;

use App\Events\SendMessageEvent;
use App\Interfaces\IMqtt;
use App\Interfaces\IUserRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendMessageForMqtt
{

    public $mqtt_service;
    public $userRepository;
    /**
     * Create the event listener.
     */
    public function __construct(IMqtt $mqtt_service,IUserRepository $userRepository)
    {
        $this ->mqtt_service = $mqtt_service;
        $this ->userRepository = $userRepository;
    }

    /**
     * Handle the event.
     */
    public function handle(SendMessageEvent $event): void
    {
        if ($event -> recipient_type == 1) {
            $data = [
                'id' => $event -> message -> id,
                'content' => $event -> message -> content,
                'sender_id' => $event -> message -> sender_id,
                'recipient_type' => $event -> recipient_type,
                'recipient_entity_id' => $event -> recipient_entity_id,
                'created_at' => $event -> message -> created_at,
            ];
        }
        else{
            $user = $this -> userRepository-> getUserForId($event -> message -> sender_id);
            $data = [
                'id' => $event -> message -> id,
                'content' => $event -> message -> content,
                'sender_id' => $event -> message -> sender_id,
                'recipient_type' => $event -> recipient_type,
                'recipient_entity_id' => $event -> recipient_entity_id,
                'created_at' => $event -> message -> created_at,
                'sender_name' => $user -> name,
            ];
        }
        

        $data = json_encode($data);
        $topic = ($event -> recipient_type == 1) ? 'user/' : 'group/';
        $this -> mqtt_service -> published($topic.$event -> recipient_entity_id, $data);
        if($event -> recipient_type != 1){
            $data = [
                'recipient_entity_id' => $event -> recipient_entity_id,
            ];
            $data = json_encode($data);
            $this -> mqtt_service -> published('groups', $data);
        }   
    }
}
