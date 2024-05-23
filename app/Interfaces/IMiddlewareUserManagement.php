<?php

namespace App\Interfaces;

interface IMiddlewareUserManagement
{
    public function getUserForRrhh_id(int $rrhh_id);
    public function createUser(array $data);
}
