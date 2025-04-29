<?php

namespace App\Services;

use App\Repositories\SpecificStudentRepository;
use App\Repositories\StudentRepository;

class StudentService
{
    protected $studentRepository,
    $specificStudentRepository;

    // Constructor Injection
    public function __construct(
        StudentRepository $studentRepository,
        SpecificStudentRepository $specificStudentRepository)
    {
        $this->studentRepository = $studentRepository;
        $this->specificStudentRepository = $specificStudentRepository;
    }

    public function getStudent(array $filters = [])
    {
        return $this->studentRepository->getStudent($filters);
    }

    public function getNonRegisteredInternList($activeBatch_id, $student_department)
    {
        return $this->specificStudentRepository->getNonRegisteredInternList($activeBatch_id, $student_department);
    }

    public function getStudentById($student_id)
    {
        return $this->specificStudentRepository->findStudentById($student_id);
    }

    public function getStudentByUserId($user_id)
    {
        return $this->specificStudentRepository->getStudentByUserId($user_id);
    }

    public function getStudentByStatusCount($year, $batch_id, $status)
    {
        return $this->studentRepository->countStudentByStatus($year, $batch_id, $status);
    }

    public function getStudentYear()
    {
        return $this->specificStudentRepository->getStudentYear();
    }

    public function getLastYearStudent()
    {
        return $this->specificStudentRepository->getLastYearStudent();
    }

    public function addStudent(array $data)
    {
        return $this->studentRepository->createStudent($data);
    }

    public function updateStudent($student_id, array $data)
    {
        return $this->studentRepository->updateStudent($student_id, $data);
    }

    public function deleteStudent($student_id)
    {
        return $this->studentRepository->deleteStudent($student_id);
    }
}
