<?php

namespace App\Repositories;

use App\Models\Department;

class DepartmentRepository
{
    public function getAllDepartment()
    {
        return Department::get();
    }
}