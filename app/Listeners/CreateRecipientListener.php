<?php

namespace App\Listeners;

use App\Events\SendMessageEvent;
use App\Interfaces\IRecipient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreateRecipientListener
{
    public $recipient_service;
    /**
     * Create the event listener.
     */
    public function __construct(IRecipient $recipient_service)
    {
        $this ->recipient_service = $recipient_service;
    }

    /**
     * Handle the event.
     */
    public function handle(SendMessageEvent $event): void
    {

        $this ->recipient_service->createRecipient($event -> message -> id, $event -> recipient_type, $event -> recipient_entity_id); 
        
    }
}
