<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
        $userId = $this->user_id;

        return [
            'user_id' => 'required',
            'name' => 'required|string',
            'nisn' => 'required|size:10|unique:students,nisn,' . $id,
            'nis' => 'required|size:4|unique:students,nis,' . $id,
            'gender' => 'required',
            'department_id' => 'required',
            'year' => 'required',
            'username' => 'required',
            'email' => 'required|unique:users,email,' . $userId,
            'phone_num' => 'required|string|min:10|max:14|unique:students,phone_num,' . $id,
            'password' => 'nullable|string|min:8|max:12',
        ];
    }
}
