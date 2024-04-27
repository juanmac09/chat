<?php

namespace App\Interfaces;

interface IMessageQuery
{


    public function getMessagesBetweenUsers(int $sender_id, int $recipient_id);
    public function getMessagesFromAGroup(int $recipient_id);
    public function getChatHistoryBetweenUsers(int $sender_id);
    public function getChatHistoryBetweenUserAndGroups(int $sender_id);
    public function getMessageReaders(int $message_id);
}
