<?php

namespace App\Interfaces\MessagesInterfaces;

use Illuminate\Database\Eloquent\Collection;

interface IMessageQuery
{
    public function getLastMessageBetweenUsers(Collection $users,int $user_id);
    public function getLastMessageFromGroup(Collection $groups);
}
