<?php

namespace App\Repositories;

use App\Models\Admin;

class AdminRepository
{
    /**
     * Retrieve a paginated list of admins with an optional search filter.
     *
     * @param string|null $searchFilter
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAdmin($searchFilter)
    {
        $query = Admin::query();

        if ($searchFilter) {
            $query->where('name', 'like', "%$searchFilter%")
                ->orWhere('phone_num', 'like', "%$searchFilter%")
                ->orWhereHas('user', function ($subQuery) use ($searchFilter) {
                    $subQuery->where('username', 'like', "%$searchFilter%")
                        ->orWhere('email', 'like', "%$searchFilter%");
                });
        }

        return $query->orderBy('created_at', 'desc')->paginate(5);
    }

    /**
     * Get admin by user ID.
     *
     * @param int $id
     * @return \App\Models\Admin|null
     */
    public function getAdminByUserId($id)
    {
        return Admin::where('user_id', $id)->first();
    }

    /**
     * Find an admin by their ID.
     *
     * @param int $id
     * @return \App\Models\Admin|null
     */
    public function findAdminById($id)
    {
        return Admin::where('id', $id)->first();
    }

    /**
     * Create a new admin with the given data.
     *
     * @param array $data
     * @return \App\Models\Admin
     */
    public function createAdmin(array $data)
    {
        return Admin::create($data);
    }

    /**
     * Update an admin's data by their ID.
     *
     * @param int $admin_id
     * @param array $data
     * @return int
     */
    public function updateAdmin($admin_id, array $data)
    {
        return Admin::where('id', $admin_id)->update($data);
    }

    /**
     * Delete an admin by their ID.
     *
     * @param int $admin_id
     * @return int
     */
    public function deleteAdmin($admin_id)
    {
        return Admin::where('id', $admin_id)->delete();
    }
}