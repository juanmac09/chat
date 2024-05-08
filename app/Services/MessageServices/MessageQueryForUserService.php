<?php

namespace App\Services\MessageServices;

use App\Interfaces\IMessageReaders;
use App\Interfaces\ITranformResponses;
use App\Interfaces\MessagesInterfaces\IMessageQueryForUsers;
use Illuminate\Support\Facades\DB;

class MessageQueryForUserService implements IMessageQueryForUsers
{

    public $messageReadersService;
    public $transformResponsesService;


    public function __construct(IMessageReaders $messageReadersService, ITranformResponses $transformResponsesService)
    {
        $this->messageReadersService = $messageReadersService;
        $this->transformResponsesService = $transformResponsesService;
    }

    /**
     * Get messages between two users.
     *
     * @param int $sender_id The ID of the sender user.
     * @param int $recipient_id The ID of the recipient user.
     * @return \Illuminate\Support\Collection A collection of messages between the two users.
     */
    public function getMessagesBetweenUsers(int $sender_id, int $recipient_id)
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

        return $this->transformResponsesService->transformResponse($data, $this->messageReadersService);
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


        return $this->transformResponsesService->transformResponse($data, $this->messageReadersService);
    }


    /**
     * Count unread messages for a specific user.
     *
     * @param int $user_id The ID of the user to get the unread messages for.
     * @return \Illuminate\Support\Collection A collection of unread messages for the specified user.
     */
    public function countMessageNotReads(int $user_id)
    {
        $unreadMessagesCount = DB::table('chatApp.users AS u')
            ->select('u.id AS sender_id', DB::raw('COUNT(*) AS unread_messages_count'))
            ->join('chatApp.messages AS m', 'u.id', '=', 'm.sender_id')
            ->join('chatApp.recipients AS r', 'm.id', '=', 'r.message_id')
            ->leftJoin('chatApp.message_reads AS mr', function ($join) {
                $join->on('m.id', '=', 'mr.message_id')
                    ->on('r.recipient_entity_id', '=', 'mr.user_id');
            })
            ->where('r.recipient_entity_id', $user_id)
            ->where('r.recipient_type', 'user')
            ->whereNull('mr.read_at')
            ->groupBy('u.id')
            ->get();


        return $unreadMessagesCount;
    }

    /**
     * Count unread messages for a specific user.
     *
     * @param int $user_id The ID of the user to get the unread messages for.
     * @return \Illuminate\Support\Collection A collection of unread messages for the specified user.
    */
    public function countUnreadMessagesPerUser(int $sender, int $recipient)
    {

        $ids = DB::table('chatApp.users AS u')
            ->select('m.id AS id')
            ->join('chatApp.messages AS m', 'u.id', '=', 'm.sender_id')
            ->join('chatApp.recipients AS r', 'm.id', '=', 'r.message_id')
            ->leftJoin('chatApp.message_reads AS mr', function ($join) {
                $join->on('m.id', '=', 'mr.message_id')
                    ->on('r.recipient_entity_id', '=', 'mr.user_id');
            })
            ->where('r.recipient_entity_id', $recipient)
            ->where('r.recipient_type', 'user')
            ->whereNull('mr.read_at')
            ->where('u.id', $sender)
            ->orderBy('u.id')
            ->get();

        return $ids;
    }
}
