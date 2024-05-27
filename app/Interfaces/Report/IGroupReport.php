<?php

namespace App\Interfaces\Report;

interface IGroupReport
{
    public function getInactiveGroups(int $limitTime);
}
