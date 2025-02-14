<?php

namespace App\Repositories;

use App\Models\Group;

class GroupRepository
{
    public function createGroup($data)
    {
        return Group::create($data);
    }

    public function getLastGroup()
    {
        return Group::latest()->first();
    }
}