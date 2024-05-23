<?php

namespace App\Services;

use App\Interfaces\IMiddlewareUserManagement;
use App\Models\User;

class MiddlewareUserManagementService implements IMiddlewareUserManagement
{

    /**
     * Get a user based on the provided RRHH ID.
     *
     * @param int $rrhh_id The RRHH ID of the user to retrieve.
     * @return \App\Models\User|null The user with the specified RRHH ID, or null if not found.
     *
     * @throws \Exception If there is an error retrieving the user.
     */
    public function getUserForRrhh_id(int $rrhh_id)
    {
        $user = User::where('rrhh_id', $rrhh_id)->first();
        return $user;
    }
    /**
     * Create a new user.
     *
     * @param array $data The data to create a new user.
     * @return \App\Models\User The newly created user.
     */
    public function createUser(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'rrhh_id' => $data['rrhh_id'],
        ]);

        return $user;
    }
}
