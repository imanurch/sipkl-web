<?php

namespace App\Repositories;

use DateTime;
use App\Models\Logbook;

class LogbookRepository
{
    /**
     * Get and group logbooks by month and year for a student in a specific batch.
     *
     * @param int $batch_id
     * @param int $student_id
     * @return \Illuminate\Support\Collection
     */
    public function getLogbookByStudentIdAndBatch($batch_id, $student_id)
    {
        return Logbook::whereHas('internship', function ($query) use ($batch_id) {
            $query->where('batch_id', $batch_id);
        })->where('student_id', $student_id)->get()->groupBy(function ($log) {
            $datetime = new DateTime($log->start_date);
            return $datetime->format('F Y');
        });
    }

    /**
     * Get logbooks by student ID, date, and batch ID.
     *
     * @param int $student_id
     * @param string $date
     * @param int $batch_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByStudentIdAndDateAndBatch($student_id, $date, $batch_id)
    {
        return Logbook::whereHas('internship', function ($query) use ($batch_id) {
            $query->where('batch_id', $batch_id);
        })->where('student_id', $student_id)->where('date', $date)->get();
    }

    /**
     * Find a logbook by its ID.
     *
     * @param int $id
     * @return \App\Models\Logbook|null
     */
    public function getLogbookByLogbookId($id)
    {
        return Logbook::find($id);
    }

    /**
     * Count logbooks by advisor status (unconfirmed, accepted, or revised).
     *
     * @param string $status
     * @param int $batch_id
     * @param int $advisor_id
     * @return int
     */
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

    /**
     * Check if all logbooks are completed (no unconfirmed or revised entries) for a student and internship.
     *
     * @param int $internship_id
     * @param int $student_id
     * @return bool
     */
    public function checkIsCompleteLogbookByInternshipAndStudentId($internship_id, $student_id)
    {
        $incomplete = Logbook::where('student_id', $student_id)->where('internship_id', $internship_id)->whereIn('status', ['0', '2'])->count();
        if ($incomplete == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get and group logbooks by month and year for a student and internship.
     *
     * @param int $student_id
     * @param int $internship_id
     * @return \Illuminate\Support\Collection
     */
    public function getLogbookByStudentAndInternshipId($student_id, $internship_id)
    {
        return Logbook::where('student_id', $student_id)->where('internship_id', $internship_id)->get()->groupBy(function ($log) {
            $datetime = new DateTime($log->start_date);
            return $datetime->format('F Y');
        });
    }
}
