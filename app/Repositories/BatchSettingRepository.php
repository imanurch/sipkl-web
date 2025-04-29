<?php

namespace App\Repositories;

use App\Models\Batch;

class BatchSettingRepository
{
    /**
     * Deactivate currently active batch (set status = 0).
     *
     * @return int Number of affected rows
     */
    public function deactivateCurrentActiveBatch()
    {
        return Batch::where('status', '1')->update(['status' => '0']);
    }

    /**
     * Set the selected batch as active (status = 1).
     *
     * @param int $id
     * @return int Number of affected rows
     */
    public function setActiveBatch($id)
    {
        return Batch::where('id', $id)->update(['status' => '1']);
    }
}
