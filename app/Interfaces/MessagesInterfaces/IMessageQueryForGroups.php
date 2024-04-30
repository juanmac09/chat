<?php

namespace App\Interfaces\MessagesInterfaces;

interface IMessageQueryForGroups
{
    public function getMessagesFromAGroup(int $recipient_id);
    public function getChatHistoryBetweenUserAndGroups(int $sender_id);
}
