<?php

namespace App\Services\ArchiveGroups;

use App\Interfaces\ArchiveGroups\IArchiveGroup;
use App\Models\Group;

class ArchiveGroupService implements IArchiveGroup
{
    /**
     * Archives a group by updating its 'archived' attribute to 1.
     *
     * @param int $group_id The ID of the group to be archived.
     *
     * @return void
     */
    public function archiveGroup(int $group_id)
    {
        $group = Group::find($group_id);
        $group->update([
            'archived' => 1,
        ]);
    }
}
