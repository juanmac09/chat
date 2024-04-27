<?php

namespace App\Interfaces;

use App\Models\User;

interface IAdvancedGroups
{
    public function addParticipants(array $participants,int $group_id);
    public function removeParticipants(array $participants,int $group_id);
    public function getGroupsForUser(User $user);
}
