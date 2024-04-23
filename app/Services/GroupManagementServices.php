<?php

namespace App\Services;

use App\Interfaces\IGroupManagement;
use App\Models\Group;

class GroupManagementServices implements IGroupManagement
{
    public function create_group(string $name, array $participants)
    {
        $group= Group::create([
            'name' => $name,
        ]);

        
    }
}
