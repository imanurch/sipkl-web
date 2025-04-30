<?php

namespace App\Services;

use App\Repositories\InternRepository;
use App\Repositories\InternshipRepository;
use App\Repositories\InternshipByAdvisorRepository;

class InternshipService
{
    protected 
    $internshipRepository,
    $internRepository,
    $internshipByAdvisorRepository;

    // Constructor Injection
    public function __construct(
        InternshipRepository $internshipRepository,
        InternRepository $internRepository,
        InternshipByAdvisorRepository $internshipByAdvisorRepository
        )
    {
        $this->internshipRepository = $internshipRepository;
        $this->internRepository = $internRepository;
        $this->internshipByAdvisorRepository = $internshipByAdvisorRepository;
    }

    public function getInternship($filters = [])
    {
        return $this->internshipRepository->getInternship($filters);
    }

    public function getAllInternshipList($batch)
    {
        return $this->internshipRepository->getAllInternshipList($batch);
    }

    public function getIntern($filters = [])
    {
        return $this->internRepository->getIntern($filters);
    }

    public function getAllIntern($batch_id)
    {
        return $this->internRepository->getAllIntern($batch_id);
    }

    public function getInternCount($batch_id)
    {
        return $this->internRepository->countIntern($batch_id);
    }

    public function getInternshipListByAdvisor($advisor_id, $batch)
    {
        return $this->internshipByAdvisorRepository->getInternshipListByAdvisor($advisor_id, $batch);
    }

    public function getInternByAdvisorCount($batch_id, $advisor_id)
    {
        return $this->internshipByAdvisorRepository->countInternByAdvisor($batch_id, $advisor_id);
    }

    public function getInternByAdvisor($filters = [], $advisor_id)
    {
        return $this->internshipByAdvisorRepository->getInternByAdvisor($filters, $advisor_id);
    }

    public function getIndustryByAdvisorCount($batch_id, $advisor_id)
    {
        return $this->internshipByAdvisorRepository->countIndustryByAdvisor($batch_id, $advisor_id);
    }

    public function getIndustryByAdvisor($filters = [], $advisor_id)
    {
        return $this->internshipByAdvisorRepository->getIndustryByAdvisor($filters, $advisor_id);
    }

    public function getInternshipByStudentId($batch_id, $student_id)
    {
        return $this->internshipRepository->getInternshipByStudentId($batch_id, $student_id);
    }

    public function getInternshipByInternshipId($internship_id)
    {
        return $this->internshipRepository->findInternshipById($internship_id);
    }

    public function getInternshipByGroupId($group_id)
    {
        return $this->internshipRepository->getInternshipByGroupId($group_id);
    }

    public function addInternship(array $data)
    {
        return $this->internshipRepository->createInternship($data);
    }

    public function updateInternshipAdvisor($internship_id, $advisor_id)
    {
        return $this->internshipByAdvisorRepository->updateInternshipAdvisor($internship_id, $advisor_id);
    }

    public function deleteInternship($id)
    {
        return $this->internshipRepository->deleteInternship($id);
    }
}
