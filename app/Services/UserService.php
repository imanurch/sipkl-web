<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    protected $userRepository;

    // Constructor Injection
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function addUser(array $data)
    {
        return $this->userRepository->createUser($data);
    }

    public function getUserById($id)
    {
        return $this->userRepository->getUserById($id);
    }

    public function getVerifiedAdminUser()
    {
        return $this->userRepository->getVerifiedAdminUser();
    }

    public function updateUser($id, $data)
    {
        return $this->userRepository->updateUser($id, $data);
    }

    public function deleteUser($id)
    {
        return $this->userRepository->deleteUser($id);
    }
}
