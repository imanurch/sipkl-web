<?php

namespace App\Services;

use App\Repositories\StudentRepository;

class StudentService
{
    protected $studentRepository;

    // Constructor Injection
    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function getStudent(array $filters = [])
    {
        return $this->studentRepository->getStudent($filters);
    }

    // public function getNonInternStudentList($activeBatch_id)
    // {
    //     return $this->studentRepository->getNonInternStudentList($activeBatch_id);
    // }

    public function getNonRegisteredInternList($activeBatch_id)
    {
        return $this->studentRepository->getNonRegisteredInternList($activeBatch_id);
    }

    public function getStudentById($student_id)
    {
        return $this->studentRepository->findStudentById($student_id);
    }

    // public function getStudentIdByUserId($user_id)
    // {
    //     return $this->studentRepository->getStudentIdByUserId($user_id)->id;
    // }

    public function getStudentByUserId($user_id)
    {
        return $this->studentRepository->getStudentByUserId($user_id);
    }

    public function getStudentByStatusCount($year, $batch_id, $status)
    {
        return $this->studentRepository->countStudentByStatus($year, $batch_id, $status);
    }

    public function getStudentYear()
    {
        return $this->studentRepository->getStudentYear();
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
