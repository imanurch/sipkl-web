<?php

namespace App\Services;

use App\Repositories\GroupMemberRepository;

class GroupMemberService
{
    protected $groupMemberRepository;

    // Constructor Injection
    public function __construct(GroupMemberRepository $groupMemberRepository)
    {
        $this->groupMemberRepository = $groupMemberRepository;
    }

    public function addMember($data)
    {
        return $this->groupMemberRepository->createGroupMember($data);
    }
}
