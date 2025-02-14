<?php

namespace App\Services;

use App\Repositories\InternshipRepository;

class InternshipService
{
    protected $internshipRepository;

    // Constructor Injection
    public function __construct(InternshipRepository $internshipRepository)
    {
        $this->internshipRepository = $internshipRepository;
    }

    public function getInternship($filters = [])
    {
        return $this->internshipRepository->getInternship($filters);
    }

    public function getIntern($filters = [])
    {
        return $this->internshipRepository->getIntern($filters);
    }

    public function getInternCount($batch_id)
    {
        return $this->internshipRepository->countIntern($batch_id);
    }

    public function getInternByAdvisorCount($batch_id, $advisor_id)
    {
        return $this->internshipRepository->countInternByAdvisor($batch_id, $advisor_id);
    }

    public function getInternByAdvisor($filters = [], $advisor_id)
    {
        return $this->internshipRepository->getInternByAdvisor($filters, $advisor_id);
    }

    public function getIndustryByAdvisorCount($batch_id, $advisor_id)
    {
        return $this->internshipRepository->countIndustryByAdvisor($batch_id, $advisor_id);
    }

    public function getIndustryByAdvisor($filters = [], $advisor_id)
    {
        return $this->internshipRepository->getIndustryByAdvisor($filters, $advisor_id);
    }

    public function getInternshipByStudentId($batch_id, $student_id)
    {
        return $this->internshipRepository->getInternshipByStudentId($batch_id, $student_id);
    }    
}
