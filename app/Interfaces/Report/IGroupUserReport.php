<?php

namespace App\Interfaces\Report;

interface IGroupUserReport
{
    public function getNumberOfUsersPerGroup();
    public function getGroupParticipants();
}
