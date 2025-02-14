<?php

namespace App\Services;

use App\Repositories\GroupRepository;

class GroupService
{
    protected $groupRepository;

    // Constructor Injection
    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function addGroup($data)
    {
        return $this->groupRepository->createGroup($data);
    }

    public function getLastGroup()
    {
        return $this->groupRepository->getLastGroup();
    }
}
