<?php

namespace App\Interfaces\ArchiveGroups;

use App\Models\Group;

interface IUnarchiveGroup
{
    public function unarchiveGroup(Group $group);
}
