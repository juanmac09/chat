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
     * Returns the groups that the given user is a member of.
     *
     * @param User $user The user whose groups are to be retrieved.
     * @return Collection A collection of groups that the user is a member of.
     */
    public function getGroupsForUser(User $user)
    {
        $groups = $user->groups()->where('status', 1) -> where('archived',0) ->get();
        return $groups;
    }



    /**
     * Returns the participants for a given group.
     *
     * @param int $group_id The ID of the group whose participants are to be retrieved.
     *
     * @return \Illuminate\Database\Eloquent\Collection A collection of users that are members of the specified group.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the group with the given ID is not found.
     */
    public function getParticipantsForGroup(int $group_id)
    {
        $group = Group::find($group_id);
        $participants = $group->users() -> get();
        return $participants;
    }
}
