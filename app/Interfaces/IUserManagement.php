<?php

namespace App\Interfaces;

interface IUserManagement
{
    public function getUsers(int $user_id);
    public function getUserDataWithJWT();
}
