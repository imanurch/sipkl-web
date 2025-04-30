<?php

namespace App\Services;

use App\Helpers\DateFormatHelper;
use App\Repositories\RegistrationRepository;
use App\Repositories\StudentRegistrationRepository;

class RegistrationService
{
    protected $registrationRepository,
    $studentRegistrationRepository;

    // Constructor Injection
    public function __construct(
        RegistrationRepository $registrationRepository,
        StudentRegistrationRepository $studentRegistrationRepository
        )
    {
        $this->registrationRepository = $registrationRepository;
        $this->studentRegistrationRepository = $studentRegistrationRepository;
    }

    public function getRegistration($filters = [])
    {
        $data = $this->registrationRepository->getRegistration($filters);
        foreach ($data as $dt) {
            $dt->start_date = DateFormatHelper::dateFormat($dt->start_date);
            $dt->end_date = DateFormatHelper::dateFormat($dt->end_date);

            if ($dt->RegistrationDocument) {
                foreach ($dt->registrationDocument as $doc) {
                    $url = ($doc->url != '' ? $doc->url : null);
                    if ($doc->type == 'surat permohonan') {
                        $dt->surat_permohonan = $url;
                    } else if ($doc->type == 'surat balasan') {
                        $dt->surat_balasan = $url;
                    }
                }
            }
            $dt->status = match ($dt->status) {
                '0' => 'Belum Dikonfirmasi',
                '1' => 'Diterima',
                default => 'Ditolak',
            };
        }
        return $data;
    }

    public function getRegistrationByStatusCount($status, $batch_id)
    {
        return $this->registrationRepository->countRegistrationByStatus($status, $batch_id);
    }

    public function addRegistration($data)
    {
        return $this->studentRegistrationRepository->createRegistration($data);
    }

    public function getRegistrationById($id)
    {
        return $this->registrationRepository->findRegistrationById($id);
    }

    public function getRegistrationByStudentId($batch_id, $student_id)
    {
        return $this->studentRegistrationRepository->getRegistrationByStudentId($batch_id, $student_id);
    }

    public function getAllHistoryRegistrationByStudentId($student_id)
    {
        return $this->studentRegistrationRepository->getAllHistoryRegistrationByStudentId($student_id);
    }

    public function updateStatusRegistration($id, $status)
    {
        return $this->registrationRepository->updateStatusRegistration($id, $status);
    }

    public function updateRegistrationStep($id, $step)
    {
        return $this->studentRegistrationRepository->updateRegistrationStep($id, $step);
    }

    public function deleteRegistration($id)
    {
        return $this->registrationRepository->deleteRegistration($id);
    }
}
