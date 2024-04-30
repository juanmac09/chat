<?php

namespace App\Services\MessageServices;

use App\Interfaces\MessagesInterfaces\IMessageSender;
use App\Models\Message;
use App\Models\User;

class MessageSenderService implements IMessageSender
{

    /**
     * Sends a new message to the specified user.
     *
     * @param string $message The content of the message to be sent.
     * @param User $user The authenticated user who is sending the message.
     *
     * @return Message The newly created message object.
     */
    public function sendMessage(string $message, User $user)
    {
        $message = Message::create([
            'content' => $message,
            'sender_id' => $user->id,
        ]);

        return $message;
    }



    /**
     * Marks the specified message as read for the authenticated user.
     *
     * @param int $message_id The ID of the message to be marked as read.
     * @param User $user The authenticated user who is marking the message as read.
     *
     * @return Message|null The found message object, or null if no message is found.
     */
    public function markAsRead(int $message_id, User $user)
    {
        $message = Message::find($message_id);
        $message->markAsRead($user->id);
        return $message;
    }
}
