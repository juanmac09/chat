<?php

namespace App\Services;

use App\Interfaces\IUserRepository;
use App\Models\User;

class UserRepositoryServices implements IUserRepository
{
   public function getUserForId(int $userId)
   {
        $user = User::find($userId);

        return $user;
   }
}
