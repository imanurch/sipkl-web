<?php

namespace App\Repositories;

use App\Models\InternDocument;

class InternDocumentRepository
{
    // public function getAllInternDocument()
    // {
    //     return InternDocument::get();
    // }

    public function getInternDocumentByStudentId($student_id, $type)
    {
        if ($type == '') {
            return InternDocument::where('student_id', $student_id)->first();
        } else {
            return InternDocument::where('student_id', $student_id)->where('type', $type)->first();
        }
    }

    public function checkIsCompleteFinalReportByInternshipAndStudentId($internship_id, $student_id)
    {
        $complete = InternDocument::where('student_id', $student_id)->where('internship_id', $internship_id)->count();
        // dd($complete);
        if ($complete == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function createInternDocument(array $data)
    {
        return InternDocument::updateOrCreate(
            [
                'student_id' => $data['student_id'],
                'internship_id' => $data['internship_id'],
                'type' => $data['type'],
            ],
            ['url' => $data['url']]
        );
    }

    public function updateInternDocument($id, array $data)
    {
        return InternDocument::where('id', $id)->update($data);
    }

    public function deleteInternDocument($id)
    {
        return InternDocument::where('id', $id)->delete();
    }
}
