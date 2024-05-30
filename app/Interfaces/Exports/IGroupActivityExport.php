<?php

namespace App\Interfaces\Exports;

interface IGroupActivityExport
{
    public function GroupActivityExport(int $limitTime,string $time);
}
