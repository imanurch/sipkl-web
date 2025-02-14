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

    public function getAdmin($searchFilter)
    {
        return $this->adminRepository->getAdmin($searchFilter);
    }

    public function getAdminById($admin_id)
    {
        return $this->adminRepository->findAdminById($admin_id);
    }

    public function addAdmin(array $data)
    {
        return $this->adminRepository->createAdmin($data);
    }

    public function updateAdmin($admin_id, array $data)
    {
        return $this->adminRepository->updateAdmin($admin_id, $data);
    }

    public function deleteAdmin($admin_id)
    {
        return $this->adminRepository->deleteAdmin($admin_id);
    }
}
