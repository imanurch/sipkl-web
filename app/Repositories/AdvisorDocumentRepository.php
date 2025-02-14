<?php

namespace App\Repositories;

use App\Models\AdvisorDocument;

class AdvisorDocumentRepository
{
    public function getAdvisorDocumentByAdvisorIdAndBatchId($advisor_id, $batch_id)
    {
        return AdvisorDocument::where('advisor_id', $advisor_id)->where('batch_id',$batch_id);
    }

    // public function findAdvisorDocumentById($id)
    // {
    //     return AdvisorDocument::find($id);
    // }

    // public function createAdvisorDocument(array $data)
    // {
    //     return AdvisorDocument::create($data);
    // }

    public function updateAdvisorDocument($id, array $data)
    {
        return AdvisorDocument::where('id', $id)->update($data);
    }
    
    public function deleteAdvisorDocument($id)
    {
        return AdvisorDocument::where('id', $id)->delete();
    }
}