<?php

namespace App\Repositories;

use App\Models\Registration;

class RegistrationRepository
{
    /**
     * Retrieve registrations based on various filters.
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRegistration($filters = [])
    {
        $query = Registration::with(
            'group',
            'group.groupMember.student:id,name,nisn,nis,department_id',
            'group.groupMember.student.department:id,name',
            'industry:id,name',
            'registrationDocument'
        );

        // Filter by registration status
        if ($filters['status'] != null) {
            if ($filters['status'] == 'unconfirmed') {
                $query->where('batch_id', $filters['batch_id'])->where('status', '0');
            } elseif ($filters['status'] == 'accepted') {
                $query->where('batch_id', $filters['batch_id'])->where('status', '1');
            } elseif ($filters['status'] == 'rejected') {
                $query->where('batch_id', $filters['batch_id'])->where('status', '2');
            }
        }

        // Filter by search keyword
        if ($filters['search'] != null) {
            $query->where(function ($subQuery) use ($filters) {
                $subQuery->whereHas('group', function ($subSubQuery) use ($filters) {
                    $subSubQuery->where('name', 'like', '%' . $filters['search'] . '%')
                        ->orWhereHas('groupMember.student', function ($subSubQuery) use ($filters) {
                            $subSubQuery->where('name', 'like', '%' . $filters['search'] . '%');
                        });
                })->orWhereHas('industry', function ($subSubQuery) use ($filters) {
                    $subSubQuery->where('name', 'like', '%' . $filters['search'] . '%');
                });
            });
        }

        return $query->where('batch_id', $filters['batch_id'])->orderBy('created_at', 'desc')->paginate(10);
    }

    /**
     * Count the number of registrations by their status.
     *
     * @param string $status
     * @param int $batch_id
     * @return int
     */
    public function countRegistrationByStatus($status, $batch_id)
    {
        if ($status == 'unconfirmed') {
            return Registration::where('batch_id', $batch_id)->where('status', '0')->count();
        } elseif ($status == 'accepted') {
            return Registration::where('batch_id', $batch_id)->where('status', '1')->count();
        } elseif ($status == 'rejected') {
            return Registration::where('batch_id', $batch_id)->where('status', '2')->count();
        }
    }

    /**
     * Find a registration by its ID with related models.
     *
     * @param int $id
     * @return \App\Models\Registration|null
     */
    public function findRegistrationById($id)
    {
        return Registration::with(
            'group',
            'group.groupMember.student:id,nisn,name,department_id',
            'group.groupMember.student.department:id,name',
            'industry:id,name,address',
            'registrationDocument'
        )->where('id', $id)->first();
    }

    /**
     * Update the registration status (accept or reject).
     *
     * @param int $id
     * @param string $status
     * @return int
     */
    public function updateStatusRegistration($id, $status)
    {
        if ($status == 'accept') {
            return Registration::where('id', $id)->update(['status' => '1']);
        } else if ($status == 'reject') {
            return Registration::where('id', $id)->update(['status' => '2']);
        }
    }

    /**
     * Delete a registration by its ID.
     *
     * @param int $id
     * @return int
     */
    public function deleteRegistration($id)
    {
        return Registration::where('id', $id)->delete();
    }
}
