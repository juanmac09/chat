<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        return $this->belongsToMany(Message::class);
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

    
}
