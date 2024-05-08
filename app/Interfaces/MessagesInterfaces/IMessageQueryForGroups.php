<?php

namespace App\Interfaces\MessagesInterfaces;

interface IMessageQueryForGroups
{
    public function getMessagesFromAGroup(int $recipient_id);
    public function getChatHistoryBetweenUserAndGroups(int $sender_id);
    public function countMessageNotReads(int $user_id);
    public function countUnreadMessagesPerGroup(int $sender, int $recipient);
 
}
