<?php

namespace App\Services;

use App\Repositories\DepartmentRepository;

class DepartmentService
{
    protected $departmentRepository;

    // Constructor Injection
    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function getAllDepartment()
    {
        return $this->departmentRepository->getAllDepartment();
    }
}
