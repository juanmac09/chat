<?php

namespace App\Services\Report;

use App\Interfaces\Report\IGroupUserReport;
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
}
