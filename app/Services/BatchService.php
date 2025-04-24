<?php

namespace App\Services;

use App\Repositories\BatchRepository;

class BatchService
{
    protected $batchRepository;

    // Constructor Injection
    public function __construct(BatchRepository $batchRepository)
    {
        $this->batchRepository = $batchRepository;
    }

    public function getAllBatch($searchFilters)
    {
        return $this->batchRepository->getAllBatch($searchFilters);
    }

    // public function getBatchByStatus($status)
    // {
    //     return $this->batchRepository->getBatchByStatus($status);
    // }

    public function getBatchByStatus($status)
    {
        if ($status == 'active') {
            return $this->batchRepository->getBatchByActiveStatus($status);
        } else {
            return $this->batchRepository->getBatchByNonActiveStatus($status);
        }
    }

    // public function getActiveOrLastBatch()
    // {
    //     return $this->batchRepository->getActiveOrLastBatch();
    // }

    public function getActiveOrLastBatch()
    {
        $activeBatch = $this->batchRepository->getActiveBatch();
        if ($activeBatch != null) {
            return $activeBatch;
        } else {
            return $this->batchRepository->getLastBatch();
        }
    }

    public function createBatch($data)
    {
        return $this->batchRepository->createBatch($data);
    }

    public function updateBatch($id, $data)
    {
        return $this->batchRepository->updateBatch($id, $data);
    }

    public function setActiveBatch($id)
    {
        $this->batchRepository->deactivateCurrentActiveBatch();
        return $this->batchRepository->setActiveBatch($id);
    }

    public function deleteBatch($id)
    {
        return $this->batchRepository->deleteBatch($id);
    }
}
