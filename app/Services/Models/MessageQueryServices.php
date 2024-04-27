<?php

namespace App\Services\Models;

use App\Interfaces\IMessageQuery;
use Illuminate\Support\Facades\DB;

class MessageQueryServices implements IMessageQuery
{
    /**
     * Get messages between two users.
     *
     * @param int $sender_id The ID of the sender user.
     * @param int $recipient_id The ID of the recipient user.
     * @return \Illuminate\Support\Collection A collection of messages between the two users.
     */
    public function getMessagesBetweenUsers(int $sender_id, int $recipient_id)
    {
        // Realizar la consulta para obtener los mensajes entre dos usuarios
        $data = DB::table('chatApp.users AS sender')
            ->select([
                'messages.id',
                'sender.name as sender_name',
                'messages.sender_id',
                'messages.content',
                'recipients.recipient_entity_id as recipient_id',
                'recipient.name as recipient_name',
                'messages.created_at'
            ])
            ->join('chatApp.messages', 'sender.id', '=', 'messages.sender_id')
            ->join('chatApp.recipients', 'messages.id', '=', 'recipients.message_id')
            ->join('chatApp.users AS recipient', 'recipient.id', '=', 'recipients.recipient_entity_id')
            ->where(function ($query) use ($sender_id, $recipient_id) {
                $query->where(function ($query) use ($sender_id, $recipient_id) {
                    $query->where('chatApp.messages.sender_id', $sender_id)
                        ->where('chatApp.recipients.recipient_entity_id', $recipient_id);
                })->orWhere(function ($query) use ($sender_id, $recipient_id) {
                    $query->where('chatApp.messages.sender_id', $recipient_id)
                        ->where('chatApp.recipients.recipient_entity_id', $sender_id);
                });
            })
            ->where('recipients.recipient_type', 'user')
            ->get();

        // Transformar la respuesta y devolverla
        return $this->transformResponse($data);
    }

    /**
     * Get messages from a group.
     *
     * @param int $recipient_id The ID of the recipient entity (user or group).
     * @return \Illuminate\Support\Collection A collection of messages from the specified group.
     */
    public function getMessagesFromAGroup(int $recipient_id)
    {
        // Realizar la consulta para obtener los mensajes de un grupo
        $data = DB::table('chatApp.messages')
            ->join('chatApp.recipients', 'messages.id', '=', 'recipients.message_id')
            ->where('recipients.recipient_entity_id', $recipient_id)
            ->where('recipients.recipient_type', 'group')
            ->get();

        // Transformar la respuesta y devolverla
        return $this->transformResponse($data);
    }

    /**
     * Get chat history between users.
     *
     * @param int $sender_id The ID of the sender user.
     * @return \Illuminate\Support\Collection A collection of messages between the two users.
     */
    public function getChatHistoryBetweenUsers(int $sender_id)
    {

        $data = DB::table('chatApp.users AS sender')
            ->select([
                'messages.id',
                'sender.name as sender_name',
                'messages.sender_id',
                'messages.content',
                'recipients.recipient_entity_id as recipient_id',
                'recipient.name as recipient_name',
                'messages.created_at'
            ])
            ->join('chatApp.messages', 'sender.id', '=', 'messages.sender_id')
            ->join('chatApp.recipients', 'messages.id', '=', 'recipients.message_id')
            ->join('chatApp.users AS recipient', 'recipient.id', '=', 'recipients.recipient_entity_id')
            ->where('chatApp.messages.sender_id', $sender_id)
            ->where('chatApp.recipients.recipient_entity_id', '<>', $sender_id)
            ->where('recipient_type', 'user')
            ->get();


        return $this->transformResponse($data);
    }

    /**
     * Get chat history between users and groups.
     *
     * @param int $sender_id The ID of the sender user.
     * @return \Illuminate\Support\Collection A collection of messages between the two users and groups.
     */
    public function getChatHistoryBetweenUserAndGroups(int $sender_id)
    {

        $data = DB::table('chatApp.messages')
            ->join('chatApp.recipients', 'messages.id', '=', 'recipients.message_id')
            ->where('messages.sender_id', $sender_id)
            ->where('recipients.recipient_type', 'group')
            ->get();


        return $this->transformResponse($data);
    }

    /**
     * Transforms the response data into a format that includes read status for each message.
     *
     * @param \Illuminate\Support\Collection $messages The collection of messages to transform.
     * @return \Illuminate\Support\Collection A collection of messages with read status included.
     */
    public function transformResponse($messages)
    {
        return $messages->map(function ($message) {

            $message->read = $this->getMessageReaders($message->id);
            return $message;
        });
    }

    /**
     * Get the readers of a specific message.
     *
     * @param int $message_id The ID of the message to get the readers for.
     * @return array An array of readers for the specified message, including their user ID, name, and read status.
     */
    public function getMessageReaders(int $message_id)
    {

        return DB::table('message_reads')
            ->join('users', 'message_reads.user_id', '=', 'users.id')
            ->select('message_reads.user_id', 'users.name', 'message_reads.read_at')
            ->where('message_id', $message_id)
            ->get()
            ->toArray();
    }
}
