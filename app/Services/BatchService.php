<?php

namespace App\Services;

use App\Repositories\BatchRepository;
use App\Repositories\BatchSettingRepository;

class BatchService
{
    protected $batchRepository, $batchSettingRepository;

    // Constructor Injection
    public function __construct(
        BatchRepository $batchRepository,
        BatchSettingRepository $batchSettingRepository
    ) {
        $this->batchRepository = $batchRepository;
        $this->batchSettingRepository = $batchSettingRepository;
    }

    /**
     * Retrieve all batches based on the provided search filters.
     * 
     * @param array $searchFilters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllBatch($searchFilters)
    {
        return $this->batchRepository->getAllBatch($searchFilters);
    }

    /**
     * Retrieve batches based on their status.
     * 
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBatchByStatus($status)
    {
        return $this->batchRepository->getBatchByStatus($status);
    }

    /**
     * Retrieve either the active batch or the last batch.
     * 
     * @return \App\Models\Batch
     */
    public function getActiveOrLastBatch()
    {
        return $this->batchRepository->getActiveOrLastBatch();
    }

    /**
     * Retrieve either the active batch or the last batch when request null.
     * 
     * @return \App\Models\Batch
     */
    public function getRelevantBatch($batchRequest)
    {
        $currentBatch = $this->batchRepository->getActiveOrLastBatch();
        $batch_id = $batchRequest ?? ($currentBatch->id ?? '');

        return $batch_id;
    }

    /**
     * Create a new batch with the provided data.
     * 
     * @param array $data
     * @return \App\Models\Batch
     */
    public function createBatch($data)
    {
        return $this->batchRepository->createBatch($data);
    }

    /**
     * Update an existing batch with new data.
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateBatch($id, $data)
    {
        return $this->batchRepository->updateBatch($id, $data);
    }

    /**
     * Set a specific batch as the active batch and deactivate the current active batch.
     * 
     * @param int $id
     * @return bool
     */
    public function setActiveBatch($id)
    {
        // Deactivate the currently active batch
        $this->batchSettingRepository->deactivateCurrentActiveBatch();
        
        // Set the new batch as the active batch
        return $this->batchSettingRepository->setActiveBatch($id);
    }

    /**
     * Delete a batch by its ID.
     * 
     * @param int $id
     * @return bool
     */
    public function deleteBatch($id)
    {
        return $this->batchRepository->deleteBatch($id);
    }
}
