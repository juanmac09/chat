<?php

namespace App\Services\ArchiveGroups;

use App\Interfaces\ArchiveGroups\IGetArchivedGroups;
use App\Models\Group;
use App\Models\User;

class GetArchivedGroupsService implements IGetArchivedGroups
{
    /**
     * Get archived groups.
     *
     * This method retrieves all archived groups from the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|Group[] A collection of archived groups.
     */
    public function getGroupsFiledGeneral()
    {
        $groups = Group::where('status', 1)->where('archived', 1)->get();
        return $groups;
    }
    /**
     * Get archived groups for a specific user.
     *
     * This method retrieves all archived groups associated with the given user.
     *
     * @param User $user The user for whom to retrieve archived groups.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|Group[] A collection of archived groups associated with the user.
     */
    public function getArchivedGroupsForAUser(User $user)
    {
        $groups = $user->groups()->where('status', 1)->where('archived', 1)->get();
        return $groups;
    }
}
