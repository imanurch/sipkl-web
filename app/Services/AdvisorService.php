<?php

namespace App\Services;

use App\Repositories\AdvisorRepository;

class AdvisorService
{
    protected $advisorRepository;

    // Constructor Injection
    public function __construct(AdvisorRepository $advisorRepository)
    {
        $this->advisorRepository = $advisorRepository;
    }

    public function getAdvisor(array $filters = [])
    {
        return $this->advisorRepository->getAdvisor($filters);
    }

    public function getAdvisorList()
    {
        return $this->advisorRepository->getAdvisorList();
    }

    public function getActiveAdvisorList($batch_id)
    {
        return $this->advisorRepository->getActiveAdvisorList($batch_id);
    }

    public function getAdvisorById($advisor_id)
    {
        return $this->advisorRepository->findAdvisorById($advisor_id);
    }

    public function getAdvisorByNIP($advisor_nip, $batch)
    {
        return $this->advisorRepository->getAdvisorByNIP($advisor_nip, $batch);
    }

    public function getAdvisorByUserId($user_id)
    {
        return $this->advisorRepository->getAdvisorByUserId($user_id);
    }

    public function getAdvisorByStatusCount($batch_id, $status)
    {
        return $this->advisorRepository->countAdvisorsByStatus($batch_id, $status);
    }

    public function addAdvisor(array $data)
    {
        return $this->advisorRepository->createAdvisor($data);
    }

    public function updateAdvisor($advisor_id, array $data)
    {
        return $this->advisorRepository->updateAdvisor($advisor_id, $data);
    }

    public function deleteAdvisor($advisor_id)
    {
        return $this->advisorRepository->deleteAdvisor($advisor_id);
    }
}
