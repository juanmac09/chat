<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
    public function recipient(): HasOne
    {
        return $this->hasOne(Recipient::class);
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
