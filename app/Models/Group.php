<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Group extends Model
{
    use HasFactory;
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];



    // Relationship
    /**
     * Get the users for the group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }



    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }


    public function usersInTheGroup(array $users) : bool{

        $data = DB::table('group_user')
        ->whereIn('id', $users)
        ->where('id', $this -> id) -> first();
        return $data ? true : false;
    }
}
