<?php

namespace App\Repositories;

use App\Models\GroupMember;

class GroupMemberRepository
{
    public function createGroupMember($data)
    {
        return GroupMember::create($data);
    }
}