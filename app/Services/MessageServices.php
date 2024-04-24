<?php
namespace App\Services;


use App\Interfaces\IMessage;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageServices implements IMessage
{
    public function sendMessage(string $message)
    {
        $message = Message::create([
            'content' => $message,
            'sender_id' => Auth::user()->id,
        ]);

        return $message;
    }
}
