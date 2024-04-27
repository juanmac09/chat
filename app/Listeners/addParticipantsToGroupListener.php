<?php

namespace App\Listeners;

use App\Events\GroupCreationEvent;
use App\Interfaces\IAdvancedGroups;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class addParticipantsToGroupListener
{

    public $group_service;

    public function __construct(IAdvancedGroups $group_service)
    {
        $this->group_service = $group_service;
    }
    

    /**
     * Handle the event.
     */
    public function handle(GroupCreationEvent $event): void
    {
        $this->group_service->addParticipants($event->participants, $event->group->id);
    }
}
