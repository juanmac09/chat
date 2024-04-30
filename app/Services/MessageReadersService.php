<?php

namespace App\Services;

use App\Interfaces\IMessageReaders;
use Illuminate\Support\Facades\DB;

class MessageReadersService implements IMessageReaders
{
    /**
     * Get the readers of a specific message.
     *
     * @param int $message_id The ID of the message to get the readers for.
     * @return array An array of readers for the specified message, including their user ID, name, and read status.
     */
    public function getMessageReaders(int $message_id)
    {

        return DB::table('message_reads')
            ->join('users', 'message_reads.user_id', '=', 'users.id')
            ->select('message_reads.user_id', 'users.name', 'message_reads.read_at')
            ->where('message_id', $message_id)
            ->get()
            ->toArray();
    }
}
