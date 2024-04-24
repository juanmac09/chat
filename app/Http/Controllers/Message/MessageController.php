<?php

namespace App\Http\Controllers\Message;

use App\Events\SendMessageEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Message\SendMessageRequest;
use App\Interfaces\IMessage;

class MessageController extends Controller
{
    public $message_service;

    public function __construct(IMessage $message_service){
        $this->message_service = $message_service;
    }

    public function sendMessage(SendMessageRequest $request){
        try {
            $message = $this->message_service->sendMessage($request -> content);
            
            SendMessageEvent::dispatch($message, $request -> recipient_type,$request -> recipient_entity_id);
            return response()->json(['success' => true], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th -> getMessage()], 200);
        }
    }
}
