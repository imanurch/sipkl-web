<?php

namespace App\Services;

use App\Repositories\InternDocumentRepository;

class InternDocumentService
{
    protected $internDocumentRepository;

    // Constructor Injection
    public function __construct(InternDocumentRepository $internDocumentRepository)
    {
        $this->internDocumentRepository = $internDocumentRepository;
    }

    public function getInternDocumentByInternshipId($internship_id)
    {
        return $this->internDocumentRepository->getInternDocumentByInternshipId($internship_id);
    }

    public function getInternDocumentByStudentId($student_id, $type)
    {
        return $this->internDocumentRepository->getInternDocumentByStudentId($student_id, $type);
    }

    public function checkIsCompleteFinalReportByInternshipAndStudentId($internship_id, $student_id)
    {
        return $this->internDocumentRepository->checkIsCompleteFinalReportByInternshipAndStudentId($internship_id, $student_id);
    }

    public function addInternDocument(array $data)
    {
        return $this->internDocumentRepository->createInternDocument($data);
    }
}
