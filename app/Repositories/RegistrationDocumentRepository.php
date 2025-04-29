<?php

namespace App\Repositories;

use App\Models\RegistrationDocument;

class RegistrationDocumentRepository
{
    /**
     * Retrieve all registration documents by registration ID.
     *
     * @param int $registration_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRegistrationDocumentByRegistrationId($registration_id)
    {
        return RegistrationDocument::where('registration_id', $registration_id)->get();
    }

    /**
     * Create a new registration document.
     *
     * @param array $data
     * @return \App\Models\RegistrationDocument
     */
    public function createRegistrationDocument(array $data)
    {
        return RegistrationDocument::create($data);
    }

    /**
     * Update the URL of a specific registration document based on registration ID and document type.
     *
     * @param int $registration_id
     * @param string $type
     * @param string $url
     * @return int
     */
    public function updateRegistrationDocument($registration_id, $type, $url)
    {
        return RegistrationDocument::where('registration_id', $registration_id)
            ->where('type', $type)
            ->update(['url' => $url]);
    }
}
