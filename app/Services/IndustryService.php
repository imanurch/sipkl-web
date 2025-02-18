<?php

namespace App\Services;

use App\Repositories\IndustryRepository;

class IndustryService
{
    protected $industryRepository;

    // Constructor Injection
    public function __construct(IndustryRepository $industryRepository)
    {
        $this->industryRepository = $industryRepository;
    }

    public function getUnconfirmedIndustry($filters = [])
    {
        return $this->industryRepository->getUnconfirmedIndustry($filters);
    }

    public function getPartnerIndustry($filters = [], $batch_id)
    {
        return $this->industryRepository->getPartnerIndustry($filters, $batch_id);
    }

    public function getRejectedIndustry($filters = [])
    {
        return $this->industryRepository->getRejectedIndustry($filters);
    }

    public function getIndustryByStatusCount($batch_id, $status)
    {
        return $this->industryRepository->countIndustryByStatus($batch_id, $status);
    }

    public function getIndustryByConfirmStatusCount($confirmStatus)
    {
        return $this->industryRepository->countIndustryByConfirmStatus($confirmStatus);
    }

    public function getPartnerIndustryList()
    {
        return $this->industryRepository->getPartnerIndustryList();
    }

    public function getIndustryById($industry_id)
    {
        return $this->industryRepository->findIndustryById($industry_id);
    }

    public function addIndustry(array $data)
    {
        return $this->industryRepository->createIndustry($data);
    }

    public function updateIndustry($industry_id, array $data)
    {
        return $this->industryRepository->updateIndustry($industry_id, $data);
    }

    public function updateIndustryRequestStatus($industry_id, $status)
    {
        return $this->industryRepository->updateIndustryRequestStatus($industry_id, $status);
    }

    public function deleteIndustry($industry_id)
    {
        return $this->industryRepository->deleteIndustry($industry_id);
    }
}
