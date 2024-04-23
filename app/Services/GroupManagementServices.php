<?php

namespace App\Services;

use App\Interfaces\IGroupManagement;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class GroupManagementServices implements IGroupManagement
{
    /**
     * Create a new group with the specified name and participants.
     *
     * @param string $name The name of the new group.
     * @param array $participants An array of user IDs who will be participants in the new group.
     *
     * @return void
     *
     * @throws \Illuminate\Database\QueryException
     */
    public function create_group(string $name, array $participants)
    {
        $user = Auth::user();
        $participants[] = $user->id;
        $group = Group::create([
            'name' => $name,
            'onwer_id' => $user->id,
        ]);

        $group->users()->attach($participants);
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
