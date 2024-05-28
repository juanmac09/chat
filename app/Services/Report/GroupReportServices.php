<?php

namespace App\Services\Report;

use App\Interfaces\Report\IGroupReport;
use Illuminate\Support\Facades\DB;

class GroupReportServices implements IGroupReport
{
    /**
     * Get a list of inactive groups based on the last message time.
     *
     * @param int $limitTime The time limit in seconds for a group to be considered inactive.
     * @return \Illuminate\Database\Eloquent\Collection A collection of inactive groups with their IDs and names.
     *
     * @throws \Exception If there is an error executing the database query.
     */
    public function getInactiveGroups(int $limitTime)
    {
        $inactiveGroups = DB::table('groups as g')
            ->leftJoin(DB::raw('(SELECT r.recipient_entity_id AS group_id, MAX(m.created_at) AS last_message_time
        FROM messages m
        INNER JOIN recipients r ON m.id = r.message_id
        WHERE r.recipient_type = "group"
        GROUP BY r.recipient_entity_id) AS last_messages'), 'g.id', '=', 'last_messages.group_id')
            ->where('g.status', '=', 1)
            ->whereNull('last_messages.group_id')
            ->orWhereRaw('TIMESTAMPDIFF(SECOND, last_messages.last_message_time, NOW()) > ' . $limitTime)
            ->select('g.id', 'g.name','g.status','g.archived')
            ->get();
        return $inactiveGroups;
    }

   
}
