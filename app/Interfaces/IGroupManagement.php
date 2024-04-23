<?php

namespace App\Interfaces;

interface IGroupManagement
{
    public function create_group(string $name, array $participants);
    public function get_groups();
    public function update_group(int $group_id,array $data);
    public function delete_group(int $group_id);
}
