<?php

namespace App\Services\Report;

use App\Interfaces\Report\IActiveGroupReport;
use Illuminate\Support\Facades\DB;

class ActiveGroupReportServices implements IActiveGroupReport
{
    /**
     * Get active groups based on the last message time within the specified limit.
     *
     * @param int $limitTime The maximum time in seconds since the last message for a group to be considered active.
     *
     * @return \Illuminate\Support\Collection A collection of active groups, each represented by an associative array with keys 'id', 'name', 'status', and 'archived'.
     */
    public function getActiveGroups(int $limitTime)
    {
        $activeGroups = DB::table('groups as g')
            ->leftJoin(DB::raw('(SELECT r.recipient_entity_id AS group_id, MAX(m.created_at) AS last_message_time
    FROM messages m
    INNER JOIN recipients r ON m.id = r.message_id
    WHERE r.recipient_type = "group"
    GROUP BY r.recipient_entity_id) AS last_messages'), 'g.id', '=', 'last_messages.group_id')
            ->where('g.status', '=', 1)
            ->whereNotNull('last_messages.group_id')
            ->whereRaw('TIMESTAMPDIFF(SECOND, last_messages.last_message_time, NOW()) <= ' . $limitTime)
            ->select('g.id', 'g.name', 'g.status', 'g.archived')
            ->get();
        return $activeGroups;
    }
}
