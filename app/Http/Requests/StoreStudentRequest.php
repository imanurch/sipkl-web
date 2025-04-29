<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
            'name' => 'required|string',
            'nisn' => 'required|size:10|unique:students,nisn',
            'nis' => 'required|size:4|unique:students,nis',
            'gender' => 'required',
            'department_id' => 'required',
            'year' => 'required',
            'username' => 'required',
            'email' => 'required|unique:users,email',
            'phone_num' => 'required|string|min:10|max:14|unique:students,phone_num,',
            'password' => 'required|string|min:8|max:12',
        ];
    }
}
