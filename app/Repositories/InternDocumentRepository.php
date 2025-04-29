<?php

namespace App\Repositories;

use App\Models\InternDocument;

class InternDocumentRepository
{
    /**
     * Get all intern documents by internship ID.
     *
     * @param  int  $internship_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getInternDocumentByInternshipId($internship_id)
    {
        return InternDocument::where('internship_id', $internship_id)->get();
    }

    /**
     * Get intern document by student ID and document type.
     *
     * @param  int  $student_id
     * @param  string  $type
     * @return \App\Models\InternDocument|null
     */
    public function getInternDocumentByStudentId($student_id, $type)
    {
        if ($type == '') {
            return InternDocument::where('student_id', $student_id)->get();
        } else {
            return InternDocument::where('student_id', $student_id)->where('type', $type)->first();
        }
    }

    /**
     * Check if the final report document is complete for a given internship and student.
     *
     * @param  int  $internship_id
     * @param  int  $student_id
     */
    public function checkIsCompleteFinalReportByInternshipAndStudentId($internship_id, $student_id)
    {
        $complete = InternDocument::where('student_id', $student_id)
            ->where('internship_id', $internship_id)
            ->where('type', 'laporan akhir')
            ->count();
        
        return $complete == 1;
    }

    /**
     * Create or update an intern document.
     *
     * @param  array  $data
     * @return \App\Models\InternDocument
     */
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

    /**
     * Update an existing intern document by its ID.
     *
     * @param  int  $id
     * @param  array  $data
     * @return int
     */
    public function updateInternDocument($id, array $data)
    {
        return InternDocument::where('id', $id)->update($data);
    }

    /**
     * Delete an intern document by its ID.
     *
     * @param  int  $id
     * @return int
     */
    public function deleteInternDocument($id)
    {
        return InternDocument::where('id', $id)->delete();
    }
}
