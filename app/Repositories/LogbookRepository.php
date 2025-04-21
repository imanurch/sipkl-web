<?php

namespace App\Repositories;

use DateTime;
use App\Models\Logbook;

class LogbookRepository
{
    public function getLogbookByStudentIdAndBatch($batch_id, $student_id)
    {
        return Logbook::whereHas('internship', function ($query) use ($batch_id) {
            $query->where('batch_id', $batch_id);
        })->where('student_id', $student_id)->get()->groupBy(function ($log) {
            $datetime = new DateTime($log->start_date);
            return $datetime->format('F Y');
        });
    }

    public function getByStudentIdAndDateAndBatch($student_id, $date, $batch_id)
    {
        return Logbook::whereHas('internship', function ($query) use ($batch_id) {
            $query->where('batch_id', $batch_id);
        })->where('student_id', $student_id)->where('date', $date)->get();
    }

    public function getLogbookByLogbookId($id){
        return Logbook::find($id);
    }

    public function countLogbookByAdvisorStatus($status, $batch_id, $advisor_id)
    {
        if ($status == 'unconfirmed') {
            return Logbook::whereHas('internship', function ($query) use ($batch_id, $advisor_id) {
                $query->where('advisor_id', $advisor_id)->where('batch_id', $batch_id);
            })->whereNotNull('activities')->where('status', '0')->count();
        } else if ($status == 'accepted') {
            return Logbook::whereHas('internship', function ($query) use ($batch_id, $advisor_id) {
                $query->where('advisor_id', $advisor_id)->where('batch_id', $batch_id);
            })->where('status', '1')->count();
        } else if ($status == 'revised') {
            return Logbook::whereHas('internship', function ($query) use ($batch_id, $advisor_id) {
                $query->where('advisor_id', $advisor_id)->where('batch_id', $batch_id);
            })->where('status', '2')->count();
        }
    }

    public function checkIsCompleteLogbookByInternshipAndStudentId($internship_id, $student_id)
    {
        $incomplete = Logbook::where('student_id', $student_id)->where('internship_id', $internship_id)->whereIn('status', ['0', '2'])->count();
        if ($incomplete == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function createLogbook(array $data)
    {
        return Logbook::create($data);
    }

    public function getLogbookByStudentAndInternshipId($student_id, $internship_id)
    {
        return Logbook::where('student_id', $student_id)->where('internship_id', $internship_id)->get()->groupBy(function ($log) {
            $datetime = new DateTime($log->start_date);
            // Format: Nama Bulan dan Tahun (misalnya "February 2025")
            return $datetime->format('F Y');
        });
    }

    public function updateLogbook($id, array $data)
    {
        return Logbook::where('id', $id)->update($data);
    }
}
