<?php

namespace App\Repositories;

use App\Models\InternDocument;

class InternDocumentRepository
{
    // public function getAllInternDocument()
    // {
    //     return InternDocument::get();
    // }

    // public function findInternDocumentById($id)
    // {
    //     return InternDocument::find($id);
    // }

    public function createInternDocument(array $data)
    {
        return InternDocument::create($data);
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