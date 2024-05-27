<?php

namespace App\Interfaces\ArchiveGroups;

use App\Models\User;

interface IGetArchivedGroups
{
    public function getGroupsFiledGeneral();
    public function getArchivedGroupsForAUser(User $user);
}
