<?php

namespace App\Services;

use App\Interfaces\IRecipient;
use App\Models\Recipient;

class RecipientServices implements IRecipient
{
    /**
     * Create a new recipient for a message.
     *
     * @param int $message_id The ID of the message to which the recipient is associated.
     * @param int $recipient_type The type of recipient (e.g., user, group, etc.).
     * @param int $recipient_entity_id The ID of the entity that the recipient is associated with.
     *
     * @return void
     */
    public function createRecipient(int $message_id, int $recipient_type, int $recipient_entity_id)
    {
        Recipient::create([
            'message_id' => $message_id,
            'recipient_type' => $recipient_type,
            'recipient_entity_id' => $recipient_entity_id,
        ]);
    }
}
