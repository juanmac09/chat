<?php

namespace App\Services\ArchiveGroups;

use App\Interfaces\ArchiveGroups\IUnarchiveGroup;
use App\Models\Group;

class UnarchiveGroupService implements IUnarchiveGroup
{
    /**
     * Unarchives a group, setting its 'archived' attribute to 0.
     *
     * @param \App\Models\Group $group The group to be unarchived.
     *
     * @return void
     *
     * @throws \Illuminate\Database\QueryException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function unarchiveGroup(Group $group)
    {
        $group->update([
            'archived' => 0,
        ]);
    }
}
