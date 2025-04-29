<?php

namespace App\Services;

use App\Repositories\AdminRepository;

class AdminService
{
    protected $adminRepository;

    // Constructor Injection
    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    /**
     * Retrieve admin data based on the search filter
     * 
     * @param array $searchFilter
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAdmin($searchFilter)
    {
        return $this->adminRepository->getAdmin($searchFilter);
    }

    /**
     * Retrieve admin data based on the user ID
     * 
     * @param int $id
     * @return \App\Models\Admin
     */
    public function getAdminByUserId($id)
    {
        return $this->adminRepository->getAdminByUserId($id);
    }

    /**
     * Retrieve admin data based on the admin ID
     * 
     * @param int $id
     * @return \App\Models\Admin
     */
    public function getAdminById($id)
    {
        return $this->adminRepository->findAdminById($id);
    }

    /**
     * Add a new admin to the system
     * 
     * @param array $data
     * @return \App\Models\Admin
     */
    public function addAdmin(array $data)
    {
        return $this->adminRepository->createAdmin($data);
    }

    /**
     * Update admin data based on the admin ID
     * 
     * @param int $admin_id
     * @param array $data
     * @return bool
     */
    public function updateAdmin($admin_id, array $data)
    {
        return $this->adminRepository->updateAdmin($admin_id, $data);
    }

    /**
     * Delete admin data based on the admin ID
     * 
     * @param int $admin_id
     * @return bool
     */
    public function deleteAdmin($admin_id)
    {
        return $this->adminRepository->deleteAdmin($admin_id);
    }
}
