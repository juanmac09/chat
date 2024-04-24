<?php

namespace App\Interfaces;

interface IRecipient
{
    public function createRecipient(int $message_id, int $recipient_type, int $recipient_entity_id);
}
