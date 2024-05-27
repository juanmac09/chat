<?php

namespace App\Interfaces\Exports;

interface IUserActivity
{
    public function exportUserActivityGeneral(int $limitTime);
    public function exportUserActivitySpecific(int $limitTime, int $type);
}
