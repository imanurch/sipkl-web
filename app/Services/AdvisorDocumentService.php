<?php

namespace App\Services;

use App\Repositories\AdvisorDocumentRepository;

class AdvisorDocumentService
{
    protected $advisorDocumentRepository;

    // Constructor Injection
    public function __construct(AdvisorDocumentRepository $advisorDocumentRepository)
    {
        $this->advisorDocumentRepository = $advisorDocumentRepository;
    }

    public function getAdvisorDocumentByAdvisorIdAndBatchId($advisor_id, $batch_id)
    {
        return $this->advisorDocumentRepository->getAdvisorDocumentByAdvisorIdAndBatchId($advisor_id, $batch_id);
    }

    public function addAdvisorDocument(array $data)
    {
        return $this->advisorDocumentRepository->createAdvisorDocument($data);
    }

    public function updateAdvisorDocument($advisor_id, array $data)
    {
        return $this->advisorDocumentRepository->updateAdvisorDocument($advisor_id, $data);
    }

    public function deleteAdvisorDocumentByAdvisorIdAndBatchId($advisor_id, $batch_id)
    {
        return $this->advisorDocumentRepository->deleteAdvisorDocumentByAdvisorIdAndBatchId($advisor_id, $batch_id);
    }

}
