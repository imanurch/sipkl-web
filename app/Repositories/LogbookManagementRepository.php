<?php

namespace App\Repositories;

use App\Models\Logbook;

class LogbookManagementRepository
{
    /**
     * Create a new logbook record.
     *
     * @param array $data
     * @return \App\Models\Logbook
     */
    public function createLogbook(array $data)
    {
        return Logbook::create($data);
    }

    /**
     * Update an existing logbook record by ID.
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function updateLogbook($id, array $data)
    {
        return Logbook::where('id', $id)->update($data);
    }
}
