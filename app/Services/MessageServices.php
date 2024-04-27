<?php

namespace App\Services;

use App\Interfaces\IMessageQuery;
use App\Interfaces\IMessage;
use App\Models\Message;
use App\Models\User;

class MessageServices implements IMessage
{

    protected $message_query;

    public function __construct(IMessageQuery $message_query){
        $this -> message_query = $message_query;
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
     * Retrieves the messages for the authenticated user.
     *
     * @param int $recipient_entity_id The ID of the recipient entity.
     * @param int $recipient_type The type of recipient entity (1 for user, 0 for group).
     * @param User $user The authenticated user.
     *
     * @return Message[]|null The messages for the specified recipient, or null if no messages are found.
    */
    public function getMessages(int $recipient_entity_id, int $recipient_type, User $user)
    {
        $messages = null;
        if ($recipient_type == 1) {
            $messages = $this -> message_query -> getMessagesBetweenUsers($user->id, $recipient_entity_id);
        } else {
            $messages = $this -> message_query -> getMessagesFromAGroup($recipient_entity_id);
        }

        return $messages;
    }

    /**
     * Retrieves the message history for the specified user.
     *
     * @param User $user The authenticated user for whom the message history is being retrieved.
     *
     * @return array An associative array containing the chat history with users and groups.
     * The 'users' key contains the chat history between the user and other users,
     * and the 'groups' key contains the chat history between the user and groups.
    */
    public function getMessageHistory(User $user)
    {
        $historyChats = [];
        $userChatHistory = $this -> message_query -> getChatHistoryBetweenUsers($user->id);
        $groupChatHistory = $this -> message_query -> getChatHistoryBetweenUserAndGroups($user->id);
        $historyChats['users'] = $userChatHistory;
        $historyChats['groups'] = $groupChatHistory;

        return $historyChats;
    }


    /**
     * Marks the specified message as read for the authenticated user.
     *
     * @param int $message_id The ID of the message to be marked as read.
     * @param User $user The authenticated user who is marking the message as read.
     *
     * @return void
    */
    public function markAsRead(int $message_id, User $user)
    {
        $message = Message::find($message_id);
        $message->markAsRead($user->id);
    }
}
