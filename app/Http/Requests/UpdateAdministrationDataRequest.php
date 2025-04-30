<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdministrationDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string',
            'phone_num' => 'required|string',
            'website' => 'required|string',
            'principal_name' => 'required|string',
            'principal_nip' => 'required|string',
            'principal_signature' => 'nullable|file',
            'school_stamp' => 'nullable|file',
            'internship_team_decree' => 'nullable|string',
        ];
    }
}
