<?php

namespace App\Interfaces;

interface IGroupManagement
{
    public function create_group(string $name, array $participants);

}
