<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssessmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'technical_aspect[]' => 'nullable|string',
            'technical_score[]' => 'nullable|numeric',
            'dicipline' => 'nullable|numeric',
            'teamwork' => 'nullable|numeric',
            'initiative' => 'nullable|numeric',
            'responsibility' => 'nullable|numeric',
            'honest' => 'nullable|numeric',
            'attitude' => 'nullable|numeric',
            'writing' => 'nullable|numeric',
            'on_time' => 'nullable|numeric',
            'orderly' => 'nullable|numeric',
            'final_report' => 'nullable|numeric',
            'final_test' => 'nullable|numeric',
        ];
    }
}
