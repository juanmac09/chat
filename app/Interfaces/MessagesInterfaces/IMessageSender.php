<?php

namespace App\Interfaces\MessagesInterfaces;

use App\Models\User;

interface IMessageSender
{
    public function sendMessage(string $message, User $user);
    public function markAsRead(int $message_id, User $user);
}
