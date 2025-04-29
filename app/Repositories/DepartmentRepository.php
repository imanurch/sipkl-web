<?php

namespace App\Repositories;

use App\Models\Department;

class DepartmentRepository
{
    /**
     * Get all departments.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllDepartment()
    {
        return Department::get();
    }
}
