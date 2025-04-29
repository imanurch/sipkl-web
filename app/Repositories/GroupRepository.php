<?php

namespace App\Repositories;

use App\Models\Group;

class GroupRepository
{
    /**
     * Create a new group record.
     *
     * @param  array  $data
     * @return \App\Models\Group
     */
    public function createGroup($data)
    {
        return Group::create($data);
    }

    /**
     * Get the most recently created group.
     *
     * @return \App\Models\Group|null
     */
    public function getLastGroup()
    {
        return Group::latest()->first();
    }
}
