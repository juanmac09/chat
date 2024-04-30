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


    public function __construct(IMessageReaders $messageReadersService, ITranformResponses $transformResponsesService){
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

        $data = DB::table('chatApp.messages')
            ->join('chatApp.recipients', 'messages.id', '=', 'recipients.message_id')
            ->where('recipients.recipient_entity_id', $recipient_id)
            ->where('recipients.recipient_type', 'group')
            ->get();


        return $this -> transformResponsesService->transformResponse($data,$this -> messageReadersService);
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


            return $this -> transformResponsesService->transformResponse($data,$this -> messageReadersService);
    }
}
