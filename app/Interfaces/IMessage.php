<?php

namespace App\Interfaces;

use App\Models\User;

interface IMessage
{
    public function sendMessage(string $message, User $user);
    public function getMessages(int $recipient_entity_id, int $recipient_type,User $user);
    public function getMessageHistory(User $user);
    public function markAsRead(int $message_id,User $user);
}
