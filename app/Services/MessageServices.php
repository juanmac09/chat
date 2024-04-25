<?php

namespace App\Services;


use App\Interfaces\IMessage;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageServices implements IMessage
{
    /**
     * Sends a new message.
     *
     * @param string $message The content of the message.
     *
     * @return Message The created message object.
     */
    public function sendMessage(string $message)
    {
        $message = Message::create([
            'content' => $message,
            'sender_id' => Auth::user()->id,
        ]);

        return $message;
    }

    /**
     * Retrieves messages based on the recipient type and entity id.
     *
     * @param int $recipient_entity_id The entity id of the recipient.
     * @param int $recipient_type The type of recipient, either 1 for a user or another value for a group.
     *
     * @return Message[]|null The messages between the sender and the recipient, or null if no messages are found.
     */
    public function getMessages(int $recipient_entity_id, int $recipient_type)
    {
        $messages = null;
        if ($recipient_type == 1) {
            $messages = Message::getMessagesBetweenUsers(Auth::user()->id, $recipient_entity_id);
        } else {
            $messages = Message::getMessagesFromAGroup($recipient_entity_id);
        }

        return $messages;
    }

    /**
     * Retrieves the chat history for the authenticated user.
     *
     * @return array The chat history, containing user and group chat history.
     */
    public function getMessageHistory()
    {
        $historyChats = [];
        $userChatHistory = Message::getChatHistoryBetweenUsers(Auth::user()->id);
        $groupChatHistory = Message::getChatHistoryBetweenUserAndGroups(Auth::user()->id);
        $historyChats['users'] = $userChatHistory;
        $historyChats['groups'] = $groupChatHistory;

        return $historyChats;
    }


    /**
     * Marks the specified message as read.
     *
     * @param int $message_id The ID of the message to be marked as read.
     *
     * @return void
     */
    public function markAsRead(int $message_id)
    {
        $message = Message::find($message_id);
        $message->markAsRead(Auth::user() -> id);
    }
}
