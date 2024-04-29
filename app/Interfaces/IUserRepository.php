<?php

namespace App\Interfaces;

interface IUserRepository
{
    public function getUserForId(int $userId);
}
