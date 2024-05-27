<?php

namespace App\Exports\User;

use Maatwebsite\Excel\Concerns\WithEvents;

class UserActivitySpecificExport implements WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function registerEvents() :array
    {
        return [];
    }
}
