<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIndustryRequest extends FormRequest
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
        $id = $this->route('id');

        return [
            'name' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|email|unique:industries,email,' . $id,
            'phone_num' => 'required|string|min:10|max:14|unique:industries,phone_num,' . $id,
            'leader_name' => 'required|string',
            'status' => 'nullable|string|min:10|max:14|unique:industries,phone_num,' . $id,

        ];
    }
}
