<?php

namespace App\Services\Exports;

use App\Exports\Report\ReportGeneral;
use App\Interfaces\Exports\IReportGeneralExport;
use App\Interfaces\Report\IActiveGroupReport;
use App\Interfaces\Report\IGroupReport;
use App\Interfaces\Report\IGroupUserReport;
use App\Interfaces\Report\IUserReport;
use Maatwebsite\Excel\Facades\Excel;

class ReportGeneralExportServices implements IReportGeneralExport
{ 
    public $userReportService;
    public $inactiveGroupReportService;
    public $activeGroupReportService;
    public $groupUserReportService;
    public function __construct(IUserReport $userReportService, IGroupReport $inactiveGroupReportService,IActiveGroupReport $activeGroupReportService, IGroupUserReport $groupUserReportService)
    {
        $this -> userReportService = $userReportService;
        $this -> inactiveGroupReportService = $inactiveGroupReportService;
        $this -> activeGroupReportService = $activeGroupReportService;
        $this -> groupUserReportService = $groupUserReportService;
    }

    public function ReportGeneralExport(int $limitTime)
    {
        $activeUsers = $this -> userReportService -> getGeneralActiveUsers($limitTime);
        $inactiveUser = $this -> userReportService -> getGeneralInactiveUsers($limitTime);
        $activeGroups = $this -> activeGroupReportService -> getActiveGroups($limitTime);
        $inactiveGroups = $this -> inactiveGroupReportService -> getInactiveGroups($limitTime);
        $groupParticipants = $this -> groupUserReportService -> getNumberOfUsersPerGroup();

        return Excel::download(new ReportGeneral($activeUsers,$inactiveUser,$activeGroups,$inactiveGroups,$groupParticipants),'reportGeneral.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }
}
