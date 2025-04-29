<?php

namespace App\Repositories;

use App\Models\GroupMember;

class GroupMemberRepository
{
    /**
     * Create a new group member record.
     *
     * @param  array  $data
     * @return \App\Models\GroupMember
     */
    public function createGroupMember($data)
    {
        return GroupMember::create($data);
    }
}
