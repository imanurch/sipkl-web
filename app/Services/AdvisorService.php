<?php

namespace App\Services;

use App\Repositories\AdvisorRepository;
use App\Repositories\SpecificAdvisorRepository;

class AdvisorService
{
    protected $advisorRepository,
        $specificAdvisorRepository;

    // Constructor Injection
    public function __construct(
        AdvisorRepository $advisorRepository,
        SpecificAdvisorRepository $specificAdvisorRepository
    ) {
        $this->advisorRepository = $advisorRepository;
        $this->specificAdvisorRepository = $specificAdvisorRepository;
    }

    /**
     * Retrieve advisor data based on filters.
     * 
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAdvisor(array $filters = [])
    {
        return $this->advisorRepository->getAdvisor($filters);
    }

    /**
     * Retrieve a list of all advisors.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAdvisorList()
    {
        return $this->advisorRepository->getAdvisorList();
    }

    /**
     * Retrieve a list of active advisors based on the batch ID.
     * 
     * @param int $batch_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveAdvisorList($batch_id)
    {
        return $this->specificAdvisorRepository->getActiveAdvisorList($batch_id);
    }

    /**
     * Retrieve advisor data based on the advisor ID.
     * 
     * @param int $advisor_id
     * @return \App\Models\Advisor
     */
    public function getAdvisorById($advisor_id)
    {
        return $this->specificAdvisorRepository->findAdvisorById($advisor_id);
    }

    /**
     * Retrieve advisor data based on the NIP and batch.
     * 
     * @param string $advisor_nip
     * @param string $batch
     * @return \App\Models\Advisor
     */
    public function getAdvisorByNIP($advisor_nip, $batch)
    {
        return $this->specificAdvisorRepository->getAdvisorByNIP($advisor_nip, $batch);
    }

    /**
     * Retrieve advisor data based on the user ID.
     * 
     * @param int $user_id
     * @return \App\Models\Advisor
     */
    public function getAdvisorByUserId($user_id)
    {
        return $this->specificAdvisorRepository->getAdvisorByUserId($user_id);
    }

    /**
     * Retrieve count of advisors based on batch ID and status.
     * 
     * @param int $batch_id
     * @param string $status
     * @return int
     */
    public function getAdvisorByStatusCount($batch_id, $status)
    {
        return $this->advisorRepository->countAdvisorsByStatus($batch_id, $status);
    }

    /**
     * Add a new advisor to the system.
     * 
     * @param array $data
     * @return \App\Models\Advisor
     */
    public function addAdvisor(array $data)
    {
        return $this->advisorRepository->createAdvisor($data);
    }

    /**
     * Update existing advisor data based on the advisor ID.
     * 
     * @param int $advisor_id
     * @param array $data
     * @return bool
     */
    public function updateAdvisor($advisor_id, array $data)
    {
        return $this->advisorRepository->updateAdvisor($advisor_id, $data);
    }

    /**
     * Delete an advisor based on the advisor ID.
     * 
     * @param int $advisor_id
     * @return bool
     */
    public function deleteAdvisor($advisor_id)
    {
        return $this->advisorRepository->deleteAdvisor($advisor_id);
    }
}
