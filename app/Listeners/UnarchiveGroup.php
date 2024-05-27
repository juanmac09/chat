<?php

namespace App\Listeners;

use App\Events\SendMessageEvent;
use App\Interfaces\ArchiveGroups\IUnarchiveGroup;
use App\Interfaces\IGroupRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UnarchiveGroup
{
    public $groupRepository;
    public  $archiveGroupSerive;
    public function __construct(IGroupRepository $groupRepository,  IUnarchiveGroup $archiveGroupSerive)
    {
        $this->groupRepository = $groupRepository;
        $this->archiveGroupSerive = $archiveGroupSerive;
    }

    /**
     * Handle the event.
     */
    public function handle(SendMessageEvent $event): void
    {
        if ($event->recipient_type == 2) {
            $group = $this->groupRepository->getGroupForId($event->recipient_entity_id);
            if ($group -> archived == 1) {
                $this -> archiveGroupSerive -> unarchiveGroup($group);
            }
        }
    }
}
