<?php

namespace App\Repositories;

use App\Models\Signature;

class SignatureRepository
{
    public function getPrincipalSignature()
    {
        return Signature::where('position','principal')->first();
    }
}