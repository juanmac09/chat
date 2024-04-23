<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Interfaces\IGroupManagement;

class GroupManagementController extends Controller
{
    public $group_service;

    public function __construct(IGroupManagement $group_service){
        $this -> group_service = $group_service;
    }

    public function create_group(){

    }
}
