<?php

namespace App\Interfaces\Report;

interface IActiveGroupReport
{
    public function getActiveGroups(int $limitTime);
}
