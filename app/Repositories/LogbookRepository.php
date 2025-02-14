<?php

namespace App\Repositories;

use App\Models\Logbook;

class LogbookRepository
{
    public function getLogbookByStudentIdAndBatch($batch_id, $student_id)
    {
        // dd($batch_id, $student_id);
        // dd(
        //     Logbook::whereHas('internship', function ($query) use ($batch_id) {
        //         $query->where('batch_id', $batch_id);
        //     })->where('student_id', $student_id)->with('student:id,name,department_id,nisn')->get()
        // );
        return Logbook::whereHas('internship', function ($query) use ($batch_id) {
            $query->where('batch_id', $batch_id);
        })->where('student_id', $student_id)->get();
    }

    public function getByStudentIdAndDateAndBatch($student_id, $date, $batch_id)
    {
        return Logbook::whereHas('internship', function ($query) use ($batch_id) {
            $query->where('batch_id', $batch_id);
        })->where('student_id', $student_id)->where('date', $date)->get();
    }

    public function countLogbookByAdvisorStatus($status, $batch_id, $advisor_id)
    {
        if ($status == 'unconfirmed') {
            return Logbook::whereHas('internship', function ($query) use ($batch_id, $advisor_id) {
                $query->where('advisor_id', $advisor_id)->where('batch_id', $batch_id);
            })->where('status', '0')->count();
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

    // baru cuma cek 1 student
    public function checkIsCompleteLogbook($student_id, $batch_id)
    {
        return Logbook::whereHas('internship', function ($query) use ($batch_id) {
            $query->where('batch_id', $batch_id);
        })->where('student_id', $student_id)->whereIn('status', [0, 2])->exists();
    }

    public function createLogbook(array $data)
    {
        return Logbook::create($data);
    }

    public function updateLogbook($id, array $data)
    {
        return Logbook::where('id', $id)->update($data);
    }

    public function updateStatusLogbook($id, $status)
    {
        return Logbook::where('id', $id)->update(['status' => $status]);
    }

    public function deleteLogbook($id)
    {
        return Logbook::where('id', $id)->delete();
    }
}
