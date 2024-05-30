<?php

namespace App\Services\Report;

use App\Interfaces\Report\IMessageReport;
use Illuminate\Support\Facades\DB;

class MessageReportServices implements IMessageReport
{
    /**
     * Get messages per day for all users.
     *
     * @return array An array of objects containing user name, RRHH ID, and messages per day.
     */
    public function getMessagesPerDayAllUsers()
    {
        $message = DB::table('users as u')
            ->join('messages as m', 'm.sender_id', '=', 'u.id')
            ->selectRaw('u.name, u.rrhh_id, DATE(m.created_at) as message_date, COUNT(m.content) as message_count')
            ->groupBy('u.name', 'u.rrhh_id', DB::raw('DATE(m.created_at)'))
            ->get();

        $groupedMessages = $message->groupBy('name')->map(function ($userMessages, $userName) {
            $rrhh_id = $userMessages->first()->rrhh_id;


            $messagesByDate = $userMessages->groupBy('message_date')->map(function ($dateMessages, $date) {
                return [
                    'name' => $date,
                    'value' => $dateMessages->sum('message_count')
                ];
            })->values();
            return [
                'name' => $userName,
                'rrhh_id' => $rrhh_id,
                'series' => $messagesByDate
            ];
        })->values();

        return $groupedMessages;
    }
}
