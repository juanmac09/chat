<?php

namespace App\Interfaces;

interface IMessage
{
    public function sendMessage(string $message);
    public function getMessages(int $recipient_entity_id, int $recipient_type);
    public function getMessageHistory();
    public function markAsRead(int $message_id);
}
