<?php

namespace App\Services\Exports;

use App\Exports\User\UserActivityGeneralExport;
use App\Interfaces\Exports\IUserActivity;
use App\Interfaces\Report\IUserReport;
use Maatwebsite\Excel\Facades\Excel;

class UserActivityServices implements IUserActivity
{
    public $userReport;
    public function __construct(IUserReport $userReport)
    {
        $this->userReport = $userReport;
    }

    /**
     * Exports the general user activity report.
     *
     * @param int $limitTime The time limit for the report.
     *
     * @return void
     */
    public function exportUserActivityGeneral(int $limitTime, string $time)
    {
        $activeUsers = $this->userReport->getGeneralActiveUsers($limitTime);
        $inactiveUsers = $this->userReport->getGeneralInactiveUsers($limitTime);
        return Excel::download(new UserActivityGeneralExport($activeUsers, $inactiveUsers, $time), 'ActivityUserGeneral.xlsx');
    }

    /**
     * Exports the specific user activity report.
     *
     * @param int $limitTime The time limit for the report.
     * @param int $type The type of specific user activity to report.
     * @param string $time The time frame for the report.
     *
     * @return void
     */
    public function exportUserActivitySpecific(int $limitTime, int $type, string $time)
    {
        $activeUsers = $this->userReport->getSpecificActiveUsers($limitTime, $type);
        $inactiveUsers = $this->userReport->getSpecificInactiveUsers($limitTime, $type);
        return Excel::download(new UserActivityGeneralExport($activeUsers, $inactiveUsers, $time, $type), 'ActivityUserSpecific.xlsx');
     
    }
}
