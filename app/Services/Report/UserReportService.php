<?php

namespace App\Services\Report;

use App\Interfaces\Report\IUserReport;
use Illuminate\Support\Facades\DB;

class UserReportService implements IUserReport
{
    /**
     * Get a list of general inactive users based on a given time limit.
     *
     * @param int $limitTime The time limit in seconds. Users who haven't sent any messages or 
     *                       haven't sent any messages within this time limit will be considered inactive.
     *
     * @return \Illuminate\Database\Eloquent\Collection A collection of inactive users, each represented 
     *                                                  by their id and name.
     *
     * @throws \Exception If any error occurs during the database query.
     */
    public function getGeneralInactiveUsers(int $limitTime)
    {
        $usersInactive = DB::table('users as u')
            ->leftJoin('messages as m', 'u.id', '=', 'm.sender_id')
            ->select('u.id', 'u.name', DB::raw('MAX(m.created_at) AS last_message_time'))
            ->groupBy('u.id', 'u.name')
            ->havingRaw('last_message_time IS NULL OR TIMESTAMPDIFF(SECOND, last_message_time, NOW()) > ?', [$limitTime])
            ->get();

        return $usersInactive;
    }


    /**
     * Get a list of specific inactive users based on a given time limit and recipient type.
     *
     * @param int $limitTime The time limit in seconds. Users who haven't sent any messages or 
     *                       haven't sent any messages within this time limit will be considered inactive.
     * @param int $recipient_type The type of recipient, either 1 for 'user' or 2 for 'group'.
     *
     * @return \Illuminate\Database\Eloquent\Collection A collection of inactive users, each represented 
     *                                                  by their id and name.
     *
     * @throws \Exception If any error occurs during the database query.
     */
    public function getSpecificInactiveUsers(int $limitTime, int $recipient_type)
    {
        $type = ($recipient_type == 1) ? 'user' : 'group';

        $usersInactive = DB::table('users as u')
            ->leftJoin(DB::raw("
            (SELECT m.sender_id, MAX(m.created_at) AS last_message_time
            FROM messages m
            LEFT JOIN recipients r ON m.id = r.message_id
            WHERE r.recipient_type = '{$type}' OR r.recipient_type IS NULL
            GROUP BY m.sender_id
            ) AS last_messages
        "), 'u.id', '=', 'last_messages.sender_id')
            ->select('u.id', 'u.name', 'last_messages.last_message_time')
            ->where(function ($query) use ($limitTime) {
                $query->whereNull('last_messages.last_message_time')
                    ->orWhereRaw('TIMESTAMPDIFF(SECOND, last_messages.last_message_time, NOW()) > ?', [$limitTime]);
            })
            ->groupBy('u.id', 'u.name')
            ->get();
        return $usersInactive;
    }

    /**
     * Get a list of general active users based on a given time limit.
     *
     * @param int $limitTime The time limit in seconds. Users who have sent messages within this time limit 
     *                       will be considered active.
     *
     * @return \Illuminate\Database\Eloquent\Collection A collection of active users, each represented 
     *                                                  by their id and name.
     *
     * @throws \Exception If any error occurs during the database query.
     */
    public function getGeneralActiveUsers(int $limitTime)
    {
        $usersActive = DB::table('users as u')
            ->leftJoin('messages as m', 'u.id', '=', 'm.sender_id')
            ->select('u.id', 'u.name', DB::raw('MAX(m.created_at) as last_message_time'))
            ->groupBy('u.id', 'u.name')
            ->havingRaw('MAX(m.created_at) IS NOT NULL AND TIMESTAMPDIFF(SECOND, MAX(m.created_at), NOW()) <= ?', [$limitTime])
            ->get();
        return $usersActive;
    }

    /**
     * Get a list of specific active users based on a given time limit and recipient type.
     *
     * @param int $limitTime The time limit in seconds. Users who have sent messages within this time limit will be considered active.
     * @param int $recipient_type The type of recipient, either 1 for 'user' or 2 for 'group'.
     *
     * @return \Illuminate\Database\Eloquent\Collection A collection of active users, each represented by their id and name.
     *
     * @throws \Exception If any error occurs during the database query.
     */
    public function getSpecificActiveUsers(int $limitTime, int $recipient_type)
    {
        $type = ($recipient_type == 1) ? 'user' : 'group';

        $usersActive = DB::table('users as u')
            ->leftJoin(DB::raw("
        (SELECT m.sender_id, MAX(m.created_at) AS last_message_time
         FROM messages m
         LEFT JOIN recipients r ON m.id = r.message_id
         WHERE r.recipient_type = '{$type}' OR r.recipient_type IS NULL
         GROUP BY m.sender_id
        ) AS last_messages
    "), 'u.id', '=', 'last_messages.sender_id')
            ->select('u.id', 'u.name', 'last_messages.last_message_time')
            ->whereNotNull('last_messages.last_message_time')
            ->whereRaw('TIMESTAMPDIFF(SECOND, last_messages.last_message_time, NOW()) <= ?', [$limitTime])
            ->get();

        return $usersActive;
    }
}
