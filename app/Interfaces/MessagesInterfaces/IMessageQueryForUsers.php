<?php

namespace App\Interfaces\MessagesInterfaces;

interface IMessageQueryForUsers
{
    public function getMessagesBetweenUsers(int $sender_id, int $recipient_id, int $page = 1, int $perPage = 20);
    public function getChatHistoryBetweenUsers(int $sender_id);
    public function countMessageNotReads(int $user_id);
    public function countUnreadMessagesPerUser(int $sender, int $recipient);
}
