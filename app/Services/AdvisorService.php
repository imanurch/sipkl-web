<?php

namespace App\Services;

use App\Repositories\AdvisorDocumentRepository;
use App\Repositories\AdvisorRepository;

class AdvisorService
{
    protected $advisorRepository, $advisorDocumentRepository;

    // Constructor Injection
    public function __construct(AdvisorRepository $advisorRepository, AdvisorDocumentRepository $advisorDocumentRepository)
    {
        $this->advisorRepository = $advisorRepository;
        $this->advisorDocumentRepository = $advisorDocumentRepository;
    }

    public function getAdvisor(array $filters = [])
    {
        return $this->advisorRepository->getAdvisor($filters);
    }

    public function getAdvisorList()
    {
        return $this->advisorRepository->getAdvisorList();
    }

    public function getAdvisorById($advisor_id)
    {
        return $this->advisorRepository->findAdvisorById($advisor_id);
    }

    public function getAdvisorDocumentByAdvisorIdAndBatchId($advisor_id, $batch_id)
    {
        return $this->advisorDocumentRepository->getAdvisorDocumentByAdvisorIdAndBatchId($advisor_id, $batch_id);
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
