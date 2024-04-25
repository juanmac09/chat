<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipient extends Model
{
    use HasFactory;
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    // Relationship
    
    /**
     * Get the recipients for the message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(Recipient::class);
    }


    
}
