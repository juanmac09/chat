<?php

namespace App\Services;

use App\Interfaces\IAdvancedGroups;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdvancedGroupsServices implements IAdvancedGroups
{
    /**
     * Adds participants to a group.
     *
     * @param array $participants An array of participant IDs.
     * @param int $group_id The ID of the group to which participants will be added.
     *
     * @return array A response array containing the status code, message, and success flag.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the group with the given ID is not found.
     */
    public function addParticipants(array $participants, int $group_id)
    {
        $response = [
            'status' => 200,
            'message' => 'Participants added successfully',
            'success' => 'true',
        ];
        $group = Group::find($group_id);

        if ($group->usersInTheGroup($participants)) {
            $response = [
                'status' => 400,
                'message' => 'Some participants already exist in the group.',
                'success' => 'false',
            ];
            return $response;
        }

        $group->users()->attach($participants);
        return $response;
    }


    /**
     * Removes participants from a group.
     *
     * @param array $participants An array of participant IDs.
     * @param int $group_id The ID of the group from which participants will be removed.
     *
     * @return array A response array containing the status code, message, and success flag.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the group with the given ID is not found.
     */
    public function removeParticipants(array $participants, int $group_id)
    {
        $response = [
            'status' => 200,
            'message' => 'Participants remove successfully',
            'success' => 'true',
        ];
        $group = Group::find($group_id);

        if (!$group->usersInTheGroup($participants)) {
            $response = [
                'status' => 400,
                'message' => 'None of the participants are in the group',
                'success' => 'false',
            ];
            return $response;
        }

        $group->users()->detach($participants);
        return $response;
    }


    /**
     * Retrieves the groups that the authenticated user is a part of.
     *
     * @return \Illuminate\Database\Eloquent\Collection A collection of Group models representing the user's groups.
     */
    public function getGroupsForUser()
    {
        $user = Auth::user();
        $groups = $user->groups()->where('status', 1)->get();
        return $groups;
    }
}
