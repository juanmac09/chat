<?php

namespace App\Services\Exports;

use App\Exports\Group\GroupInfoExport;
use App\Interfaces\Exports\IExportParticipants;
use App\Interfaces\Report\IGroupUserReport;
use Maatwebsite\Excel\Facades\Excel;

class ExportParticipantsServices implements IExportParticipants
{
    public $groupUserReport;
    public function __construct(IGroupUserReport $groupUserReport = null)
    {
        $this->groupUserReport = $groupUserReport;
    }
    /**
     * Exports the group participants' information.
     *
     * @return void
     */
    public function exportGroupParticipants()
    {
        $amountParticipants = $this->groupUserReport->getNumberOfUsersPerGroup();
        $groups = $this->groupUserReport->getGroupParticipants();
        return Excel::download(new GroupInfoExport($groups, $amountParticipants), 'GroupInfo.xlsx');
    }
}
