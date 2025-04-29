<?php

namespace App\Services;

use App\Repositories\IndustryRepository;
use App\Repositories\IndustryPartnerRepository;
use App\Repositories\IndustryRejectedRepository;
use App\Repositories\IndustryUnconfirmedRepository;

class IndustryService
{
    protected $industryRepository,
        $industryUnconfirmedRepository,
        $industryPartnerRepository,
        $industryRejectedRepository;

    // Constructor Injection
    public function __construct(
        IndustryRepository $industryRepository,
        IndustryUnconfirmedRepository $industryUnconfirmedRepository,
        IndustryPartnerRepository $industryPartnerRepository,
        IndustryRejectedRepository $industryRejectedRepository
    ) {
        $this->industryRepository = $industryRepository;
        $this->industryUnconfirmedRepository = $industryUnconfirmedRepository;
        $this->industryPartnerRepository = $industryPartnerRepository;
        $this->industryRejectedRepository = $industryRejectedRepository;
    }

    public function getUnconfirmedIndustry($filters = [])
    {
        return $this->industryUnconfirmedRepository->getUnconfirmedIndustry($filters);
    }

    public function getPartnerIndustry($filters = [], $batch_id)
    {
        return $this->industryPartnerRepository->getPartnerIndustry($filters, $batch_id);
    }

    public function getRejectedIndustry($filters = [])
    {
        return $this->industryRejectedRepository->getRejectedIndustry($filters);
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
        return $this->industryPartnerRepository->getPartnerIndustryList();
    }

    public function getActivePartnerIndustryList($batch_id)
    {
        return $this->industryPartnerRepository->getActivePartnerIndustryList($batch_id);
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
        return $this->industryUnconfirmedRepository->updateIndustryRequestStatus($industry_id, $status);
    }

    public function deleteIndustry($industry_id)
    {
        return $this->industryRepository->deleteIndustry($industry_id);
    }
}
