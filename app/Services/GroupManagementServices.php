<?php

namespace App\Services;

use App\Interfaces\IGroupManagement;
use App\Models\Group;

class GroupManagementServices implements IGroupManagement
{
    /**
     * Create a new group.
     *
     * @param string $name The name of the new group.
     * @param int $owner_id The ID of the owner of the new group.
     *
     * @return \App\Models\Group The newly created group instance.
     */
    public function create_group(string $name, int $owner_id)
    {
        $group = Group::create([
            'name' => $name,
            'onwer_id' => $owner_id,
        ]);

        return $group;
    }


    /**
     * Get all active groups.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\ModelCollection
     */
    public function get_groups()
    {
        $groups = Group::where('status', 1)->get();
        return $groups;
    }


    /**
     * Update an existing group with the specified data.
     *
     * @param int $group_id The ID of the group to be updated.
     * @param array $data An associative array of key-value pairs representing the updated data.
     *
     * @return void
     *
     * @throws \Illuminate\Database\QueryException If an error occurs while updating the group.
     */
    public function update_group(int $group_id, array $data)
    {
        $group = Group::find($group_id);
        $group->update($data);
    }


    /**
     * Delete a group by its ID.
     *
     * @param int $group_id The ID of the group to be deleted.
     *
     * @return void
     *
     * @throws \Illuminate\Database\QueryException If an error occurs while deleting the group.
     */
    public function delete_group($group_id)
    {
        $group = Group::find($group_id);
        $group->update([
            'status' => 0,
        ]);
    }
}
