<?php

namespace App\Interfaces\Exports;

interface IUserActivity
{
    public function exportUserActivityGeneral(int $limitTime,string $time);
    public function exportUserActivitySpecific(int $limitTime, int $type,string $time);
}
