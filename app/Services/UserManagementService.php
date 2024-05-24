<?php

namespace App\Services;

use App\Interfaces\IUserManagement;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    /**
     * Retrieves user data from the JWT token.
     *
     * @return \Tymon\JWTAuth\Payload The payload of the JWT token.
     *
     * @throws \Tymon\JWTAuth\Exceptions\TokenInvalidException If the token is invalid.
     * @throws \Tymon\JWTAuth\Exceptions\TokenExpiredException If the token has expired.
     * @throws \Tymon\JWTAuth\Exceptions\JWTException If an error occurs while decoding the token.
     */
    public function getUserDataWithJWT()
    {
        $token = JWTAuth::parseToken();
        $payload =  $token->getPayload();
        return $payload;
    }
}
