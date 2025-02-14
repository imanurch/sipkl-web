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

    public function getBatchByStatus($status)
    {
        return $this->batchRepository->getBatchByStatus($status);
    }

    public function createBatch($data)
    {
        return $this->batchRepository->createBatch($data);
    }

    public function updateBatch($id, $data)
    {
        return $this->batchRepository->updateBatch($id, $data);
    }

    public function deleteBatch($id)
    {
        return $this->batchRepository->deleteBatch($id);
    }
}
