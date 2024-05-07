<?php

namespace App\Services;

use App\Interfaces\IGroupRepository;
use App\Models\Group;

class GroupRepositoryService implements IGroupRepository
{
    /**
     * Get a group by its ID
     *
     * @param int $groupId The ID of the group
     * @return Group The group with the specified ID
     */
    public function getGroupForId($groupId)
    {
        $group = Group::find($groupId);
        return $group;
    }
}
