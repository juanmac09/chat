<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Message extends Model
{
    use HasFactory;
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];


    // Relationship

    /**
     * Get the users for the message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users_reads(): BelongsToMany
    {
        return $this->belongsToMany(Message::class, 'message_reads');
    }


    /**
     * Get the user who sent the message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the recipients for the message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recipients(): HasMany
    {
        return $this->hasMany(Recipient::class);
    }


    /**
     * Get messages between two users.
     *
     * @param int $sender_id The ID of the sender user.
     * @param int $recipient_id The ID of the recipient user.
     *
     * @return \Illuminate\Support\Collection A collection of messages between the two users.
     */
    public static function getMessagesBetweenUsers(int $sender_id, int $recipient_id)
    {
        $data = $data = DB::table('chatApp.users AS sender')
            ->select([
                'messages.id',
                'sender.name as sender_name',
                'messages.sender_id',
                'messages.content',
                'recipients.recipient_entity_id as recipient_id',
                'recipient.name as recipient_name',
                'messages.created_at'
            ])
            ->join('chatApp.messages', 'sender.id', '=', 'messages.sender_id')
            ->join('chatApp.recipients', 'messages.id', '=', 'recipients.message_id')
            ->join('chatApp.users AS recipient', 'recipient.id', '=', 'recipients.recipient_entity_id')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('chatApp.messages.sender_id', 1)
                        ->where('chatApp.recipients.recipient_entity_id', 2);
                })->orWhere(function ($query) {
                    $query->where('chatApp.messages.sender_id', 2)
                        ->where('chatApp.recipients.recipient_entity_id', 1);
                });
            })
            ->where('recipients.recipient_type', 'user')
            ->get();


        return self::transformResponse($data);
    }

    /**
     * Get messages from a group.
     *
     * @param int $recipient_id The ID of the recipient entity (user or group).
     *
     * @return \Illuminate\Support\Collection A collection of messages from the specified group.
     */
    public static function getMessagesFromAGroup(int $recipient_id)
    {
        $data = self::whereHas('recipients', function ($query) use ($recipient_id) {
            $query->where('recipient_entity_id', $recipient_id);
            $query->where('recipient_type', 'group');
        })
            ->get();

        return self::transformResponse($data);
    }




    /**
     * Get chat history between users.
     *
     * @param int $sender_id The ID of the sender user.
     *
     * @return \Illuminate\Support\Collection A collection of messages between the two users.
     *
     * @throws \Illuminate\Database\QueryException
     */
    public static function getChatHistoryBetweenUsers(int $sender_id)
    {
        $data = DB::table('chatApp.users AS sender')
            ->select([
                'messages.id',
                'sender.name as sender_name',
                'messages.sender_id',
                'messages.content',
                'recipients.recipient_entity_id as recipient_id',
                'recipient.name as recipient_name',
                'messages.created_at'
            ])
            ->join('chatApp.messages', 'sender.id', '=', 'messages.sender_id')
            ->join('chatApp.recipients', 'messages.id', '=', 'recipients.message_id')
            ->join('chatApp.users AS recipient', 'recipient.id', '=', 'recipients.recipient_entity_id')
            ->where('chatApp.messages.sender_id', $sender_id)
            ->where('chatApp.recipients.recipient_entity_id', '<>', $sender_id)
            ->where('recipient_type', 'user')
            ->get();

        return self::transformResponse($data);
    }

    /**
     * Get chat history between users and groups.
     *
     * @param int $sender_id The ID of the sender user.
     *
     * @return \Illuminate\Support\Collection A collection of messages between the two users and groups.
     *
     * @throws \Illuminate\Database\QueryException
     */
    public static function  getChatHistoryBetweenUserAndGroups(int $sender_id)
    {
        $data = self::where('sender_id', $sender_id)
            ->whereHas('recipients', function ($query) {
                $query->where('recipient_type', 'group');
            })
            ->get();

        return self::transformResponse($data);
    }


    /**
     * Transforms the response data into a format that includes read status for each message.
     *
     * @param Collection $data The collection of messages to transform.
     *
     * @return Collection A collection of messages with read status included.
     */
    public static function transformResponse($messages)
    {
        return $messages->map(function ($message) {
            $message->read = self::getMessageReaders($message->id);
            return $message;
        });
    }
    /**
     * Get the readers of a specific message.
     *
     * @param int $message_id The ID of the message to get the readers for.
     *
     * @return array An array of readers for the specified message, including their user ID, name, and read status.
     *
     * @throws \Illuminate\Database\QueryException If there is an error while querying the database.
     */
    private static function getMessageReaders(int $message_id)
    {
        return DB::table('message_reads')
            ->join('users', 'message_reads.user_id', '=', 'users.id')
            ->select('message_reads.user_id', 'users.name', 'message_reads.read_at')
            ->where('message_id', $message_id)
            ->get()
            ->toArray();
    }

    /**
     * Marks the message as read for the specified user.
     *
     * @param int $user_id The ID of the user who is reading the message.
     *
     * @return void
     */
    public function markAsRead(int $user_id)
    {
        $this->users_reads()->attach($this->id, ['read_at' => now(), 'user_id' => $user_id]);
    }
}
