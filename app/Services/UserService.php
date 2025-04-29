<?php

namespace App\Services;

use App\Helpers\PasswordCheckHelper;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
        return $this->userRepository->createUser([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'],
        ]);
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

    public function updateAccountUser($data)
    {
        $last_password = $this->getUserById($data->user_id)->password;
        if (Hash::check($data->password, $last_password) == false) {
            throw ValidationException::withMessages([
                'password' => 'Password Anda salah!',
            ]);
        }

        if (!empty($data->new_password)) {
            $data->new_password = PasswordCheckHelper::handlePassword($data->new_password, $data->check_password);
        } else {
            throw ValidationException::withMessages([
                'new_password' => 'Password baru tidak konsisten!',
            ]);
        }

        return $this->updateUser($data->user_id, ['password' => $data->new_password]);
    }
}
