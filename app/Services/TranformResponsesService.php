<?php

namespace App\Services;

use App\Interfaces\ITranformResponses;
use App\Models\Message;

class TranformResponsesService implements ITranformResponses
{
    /**
     * Transforms the given messages by adding the readers for each message.
     *
     * @param array $messages The array of messages to be transformed.
     * @param \App\Services\ReaderMessagesService $readerMessages_service The service to get the readers for each message.
     *
     * @return array The transformed array of messages with readers added.
     */
    public function transformResponse($messages, $readerMessages_service)
    {
        return $messages->map(function ($message) use ($readerMessages_service) {

            $message->read = $readerMessages_service->getMessageReaders($message->id);
            return $message;
        });
    }
}
