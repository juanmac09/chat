<?php

namespace App\Services\MessageServices;

use App\Interfaces\MessagesInterfaces\IMessageQueryForGroups;
use App\Interfaces\MessagesInterfaces\IMessageQueryForUsers;
use App\Interfaces\MessagesInterfaces\IMessageSender;
use App\Models\Message;
use App\Models\User;

class MessageSenderService implements IMessageSender
{

    public $messageQueryForUser;
    public $messageQueryForGroup;
    public function __construct(IMessageQueryForUsers $messageQueryForUser, IMessageQueryForGroups $messageQueryForGroup)
    {
        $this->messageQueryForUser = $messageQueryForUser;
        $this->messageQueryForGroup = $messageQueryForGroup;
    }

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

    /**
     * Marks all unread messages for the specified sender and recipient as read.
     *
     * @param int $sender The ID of the sender of the messages.
     * @param int $recipient The ID of the recipient of the messages.
     * @param int $type 1 for user-to-user messages, 2 for group messages.
     *
     * @return void
     */
    public function markAllMessagesAsRead(int $sender, int $recipient, int $type)
    {
        $ids = [];
        if ($type == 1) {
            $ids = $this->messageQueryForUser->countUnreadMessagesPerUser($sender, $recipient);
        } else {
            $ids = $this->messageQueryForGroup->countUnreadMessagesPerGroup($sender, $recipient);
        }
        foreach ($ids as $id) {
            $message = Message::find($id->id);
            $message->markAsRead($recipient);
        }
    }
}
