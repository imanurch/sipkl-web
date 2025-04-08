<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function createUser(array $data)
    {
        return User::create($data);
    }

    public function getUserById($id)
    {
        return User::find($id);
    }

    public function updateUser($id, array $data)
    {
        return User::where('id', $id)->update($data);
    }

    public function deleteUser($id)
    {
        return User::where('id',$id)->delete();
    }
}