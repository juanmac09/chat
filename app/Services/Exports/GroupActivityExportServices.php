<?php

namespace App\Services\Exports;

use App\Exports\Group\GroupActivityExport;
use App\Interfaces\Exports\IGroupActivityExport;
use App\Interfaces\Report\IActiveGroupReport;
use App\Interfaces\Report\IGroupReport;
use Maatwebsite\Excel\Facades\Excel;

class GroupActivityExportServices implements IGroupActivityExport
{
    public $groupInaciveServices;
    public $groupAciveServices;
    public function __construct(IGroupReport $groupInaciveServices, IActiveGroupReport $groupAciveServices)
    {
        $this -> groupInaciveServices = $groupInaciveServices;
        $this -> groupAciveServices = $groupAciveServices;
    }

    public function GroupActivityExport(int $limitTime, string $time)
    {
        $groupActive = $this -> groupAciveServices -> getActiveGroups($limitTime);
        $groupInactive = $this -> groupInaciveServices -> getInactiveGroups($limitTime);
        return Excel::download(new GroupActivityExport($groupActive,$groupInactive,$time),'GroupActivity.xlsx');
    }
}
