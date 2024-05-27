<?php

namespace App\Console\Commands;

use App\Interfaces\ArchiveGroups\IArchiveGroup;
use App\Interfaces\Report\IGroupReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ArchiveGroups extends Command
{
    public $groupReportService;
    public $archiveGroupService;
    public function __construct(IGroupReport $groupReportService,IArchiveGroup $archiveGroupService) {
        parent::__construct();
        $this->groupReportService = $groupReportService;
        $this->archiveGroupService = $archiveGroupService;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'group:archive-groups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive inactive groups';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $groups = $this -> groupReportService -> getInactiveGroups(env('SECONDS_TO_ARCHIVE_GROUPS',3600));
        foreach ($groups as $group) {
            if ($group -> status == 1 && $group -> archived == 0) {
                $this -> archiveGroupService -> archiveGroup($group -> id);
            }
        }
    }
}
