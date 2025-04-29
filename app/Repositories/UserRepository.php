<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * Create a new user record.
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function createUser(array $data)
    {
        return User::create($data);
    }

    /**
     * Retrieve a user by their ID.
     *
     * @param int $id
     * @return \App\Models\User|null
     */
    public function getUserById($id)
    {
        return User::find($id);
    }

    /**
     * Get all verified admin users.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getVerifiedAdminUser()
    {
        return User::where('role', 'admin')
            ->whereNotNull('email_verified_at')
            ->get();
    }

    /**
     * Update a user by their ID.
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function updateUser($id, array $data)
    {
        return User::where('id', $id)->update($data);
    }

    /**
     * Delete a user by their ID.
     *
     * @param int $id
     * @return int
     */
    public function deleteUser($id)
    {
        return User::where('id', $id)->delete();
    }
}
