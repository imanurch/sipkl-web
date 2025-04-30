<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdvisorRequest extends FormRequest
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
            'name' => 'required|string',
            'nip' => 'required|size:18',
            'position_id' => 'required',
            'level_id' => 'required',
            'department_id' => 'required',
            'username' => 'required|string',
            'email' => 'required|unique:users,email|email',
            'phone_num' => 'required|unique:advisors,phone_num|string|min:10|max:14',
            'password' => 'required|string|min:8|max:12',
        ];
    }
}
