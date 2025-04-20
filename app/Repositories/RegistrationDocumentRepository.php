<?php

namespace App\Repositories;

use App\Models\RegistrationDocument;

class RegistrationDocumentRepository
{
    public function getRegistrationDocumentByRegistrationId($registration_id)
    {
        return RegistrationDocument::where('registration_id',$registration_id)->get();
    }

    public function createRegistrationDocument(array $data)
    {
        return RegistrationDocument::create($data);
    }

    public function updateRegistrationDocument($registration_id, $type, $url)
    {
        return RegistrationDocument::where('registration_id',  $registration_id)->where('type', $type)->update(['url' => $url]);
    }
}
