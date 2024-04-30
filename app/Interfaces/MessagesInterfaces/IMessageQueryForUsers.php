<?php

namespace App\Interfaces\MessagesInterfaces;

interface IMessageQueryForUsers
{
    public function getMessagesBetweenUsers(int $sender_id, int $recipient_id);
    public function getChatHistoryBetweenUsers(int $sender_id);
    public function countMessageNotReads(int $user_id);
}
