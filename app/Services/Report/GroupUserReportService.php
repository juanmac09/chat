<?php

namespace App\Services\Report;

use App\Interfaces\Report\IGroupUserReport;
use App\Models\Group;
use Illuminate\Support\Facades\DB;

class GroupUserReportService implements IGroupUserReport
{
    /**
     * Get the number of users per group.
     *
     * This method retrieves the number of users in each group.
     *
     * @return \Illuminate\Database\Eloquent\Collection A collection of groups with their IDs, names, and the number of users in each group.
     *
     * @throws \Exception If there is an error executing the database query.
     */
    public function getNumberOfUsersPerGroup()
    {
        $groups = DB::table('users')
            ->join('group_user', 'users.id', '=', 'group_user.user_id')
            ->join('chatApp.groups', 'chatApp.groups.id', '=', 'group_user.group_id')
            ->select('chatApp.groups.id', 'chatApp.groups.name', DB::raw('COUNT(*) AS users_quantity'))
            ->groupBy('chatApp.groups.id')
            ->get();

        return $groups;
    }


    /**
     * Get the group participants.
     *
     * This method retrieves the list of participants in each group along with their details.
     *
     * @return array A collection of groups with their IDs, names, status, archived status, and the list of participants in each group.
     *
     * @throws \Exception If there is an error executing the database query.
     */
    public function getGroupParticipants()
    {
        $groups = Group::with(['users:id,name,rrhh_id', 'owner:id,name,rrhh_id'])
            ->select('id', 'name', 'status', 'archived', 'created_at', 'onwer_id')
            ->where('status', 1)
            ->get();
        $formattedGroups = $groups->map(function ($group) {
            return [
                'id' => $group->id,
                'name' => $group->name,
                'status' => $group->status,
                'archived' => $group->archived,
                'created_date' => $group->created_at,
                'owner' => [
                    'name' => $group->owner->name,
                    'rrhh_id' => $group->owner->rrhh_id,
                ],
                'participants' => $group->users->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'rrhh_id' => $user->rrhh_id
                    ];
                })
            ];
        });

        return $formattedGroups;
    }
}
