<?php

namespace App\Exports\Report;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class ReportGeneral implements FromView
{
    public $activeUser;
    public $inactiveUser;
    public $activeGroups;
    public $inactiveGroups;
    public $groupParticipants;

    public function __construct(Collection $activeUser, Collection $inactiveUser, Collection $activeGroups, Collection $inactiveGroups, Collection $groupParticipants)
    {
        $this->activeUser = $activeUser;
        $this->inactiveUser = $inactiveUser;
        $this->activeGroups = $activeGroups;
        $this->inactiveGroups = $inactiveGroups;
        $this->groupParticipants = $groupParticipants;
    }
    /**
     * Prepare the view for the report general.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): View
    {
        
        return view('Reports.reportGeneral', [
            'activeUser' => count($this->activeUser),
            'inactiveUser' => count($this->inactiveUser),
            'activeGroups' => count($this->activeGroups),
            'inactiveGroups' => count($this->inactiveGroups),
            'groupParticipants' => $this->groupParticipants,
        ]);
    }
}
