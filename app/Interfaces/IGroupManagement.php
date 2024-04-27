<?php

namespace App\Interfaces;

interface IGroupManagement
{
    public function create_group(string $name, int $owner_id);
    public function get_groups();
    public function update_group(int $group_id,array $data);
    public function delete_group(int $group_id);
}
