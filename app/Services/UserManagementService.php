<?php

namespace App\Services;

use App\Interfaces\IUserManagement;
use App\Models\User;

class UserManagementService implements IUserManagement
{
    /**
     * Get a list of users, except for the one with the specified ID
     *
     * @param int $user_id The ID of the user to exclude from the list
     * @return \Illuminate\Database\Eloquent\Collection A collection of users
     */
    public function getUsers(int $user_id)
    {
        $users = User::where('id', '<>', $user_id)->get();

        return $users;
    }
}
