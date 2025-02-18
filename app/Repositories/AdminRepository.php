<?php

namespace App\Repositories;

use App\Models\Admin;

class AdminRepository
{
    public function getAdmin($searchFilter)
    {
        $query = Admin::query();

        // filter search
        if ($searchFilter != null) {
            $query->where('username', 'like', '%' . $searchFilter . '%')
                ->orWhere('email', 'like', '%' . $searchFilter . '%');
        };

        return $query->paginate(5);
    }

    // public function findAdminById($admin_id)
    // {
    //     return Admin::find($admin_id);
    // }

    public function createAdmin(array $data)
    {
        return Admin::create($data);
    }

    public function updateAdmin($admin_id, array $data)
    {
        return Admin::where('id', $admin_id)->update($data);
    }

    public function deleteAdmin($admin_id)
    {
        return Admin::where('id', $admin_id)->delete();
    }
}
