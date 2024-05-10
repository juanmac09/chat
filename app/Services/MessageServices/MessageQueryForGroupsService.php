<?php

namespace App\Services\MessageServices;

use App\Interfaces\IMessageReaders;
use App\Interfaces\ITranformResponses;
use App\Interfaces\MessagesInterfaces\IMessageQueryForGroups;
use Illuminate\Support\Facades\DB;

class MessageQueryForGroupsService implements IMessageQueryForGroups
{
    public $messageReadersService;
    public $transformResponsesService;


    public function __construct(IMessageReaders $messageReadersService, ITranformResponses $transformResponsesService)
    {
        $this->messageReadersService = $messageReadersService;
        $this->transformResponsesService = $transformResponsesService;
    }
    /**
     * Get messages from a group.
     *
     * @param int $recipient_id The ID of the recipient entity (user or group).
     * @return \Illuminate\Support\Collection A collection of messages from the specified group.
     */
    public function getMessagesFromAGroup(int $recipient_id)
    {

        $data = DB::table('chatApp.messages AS m')
            ->select('m.*', 'r.recipient_type', 'r.recipient_entity_id', 'sender.name as sender_name')
            ->join('chatApp.recipients AS r', 'm.id', '=', 'r.message_id')
            ->join('chatApp.users AS sender', 'm.sender_id', '=', 'sender.id')
            ->where('r.recipient_entity_id', '=', $recipient_id)
            ->where('r.recipient_type', '=', 'group')
            ->get();



        return $this->transformResponsesService->transformResponse($data, $this->messageReadersService);
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
            ->select('messages.*', 'chatApp.groups.name as group_name', 'chatApp.users.name as sender_name')
            ->join('chatApp.recipients', 'messages.id', '=', 'recipients.message_id')
            ->join('chatApp.groups', 'chatApp.groups.id', '=', 'recipients.recipient_entity_id')
            ->join('chatApp.users', 'chatApp.messages.sender_id', '=', 'chatApp.users.id')
            ->where('messages.sender_id', $sender_id)
            ->where('recipients.recipient_type', 'group')
            ->get();



        return $this->transformResponsesService->transformResponse($data, $this->messageReadersService);
    }
    /**
     * Count unread messages in groups for a specific user.
     *
     * @param int $user_id The ID of the user to check unread messages for.
     * @return \Illuminate\Support\Collection A collection of groups with their unread message counts.
     */
    public function countMessageNotReads(int $user_id)
    {
        $unreadMessagesCount = DB::table('messages AS m')
            ->select('g.id AS group_id', DB::raw('COUNT(*) AS unread_messages_count'))
            ->join('recipients AS r', 'm.id', '=', 'r.message_id')
            ->join('group_user AS gu', 'r.recipient_entity_id', '=', 'gu.group_id')
            ->leftJoin('message_reads AS mr', function ($join) {
                $join->on('m.id', '=', 'mr.message_id')
                    ->on('gu.user_id', '=', 'mr.user_id');
            })
            ->join('groups AS g', 'gu.group_id', '=', 'g.id')
            ->where('r.recipient_type', 'group')
            ->where('gu.user_id', $user_id)
            ->where('m.sender_id', '<>', $user_id)
            ->whereNull('mr.read_at')
            ->groupBy('g.id')
            ->get();


        return $unreadMessagesCount;
    }

    /**
     * Count unread messages in a specific group for a specific user.
     *
     * @param int $sender The ID of the user to check unread messages for.
     * @param int $recipient The ID of the group to check unread messages for.
     * @return \Illuminate\Support\Collection A collection of message IDs that are unread for the specified user and group.
     */
    public function countUnreadMessagesPerGroup(int $sender, int $recipient)
    {

        
        $ids = DB::table('messages AS m')
            ->select('m.id')
            ->join('recipients AS r', 'm.id', '=', 'r.message_id')
            ->join('group_user AS gu', 'r.recipient_entity_id', '=', 'gu.group_id')
            ->leftJoin('message_reads AS mr', function ($join) {
                $join->on('m.id', '=', 'mr.message_id')
                    ->on('gu.user_id', '=', 'mr.user_id');
            })
            ->join('groups AS g', 'gu.group_id', '=', 'g.id')
            ->where('r.recipient_type', 'group')
            ->where('gu.user_id',$recipient )
            ->where('m.sender_id', '<>',$recipient)
            ->where('g.id', $sender)
            ->whereNull('mr.read_at')
            ->get();
        return $ids;
    }
}
