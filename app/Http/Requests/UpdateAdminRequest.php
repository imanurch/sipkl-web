<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
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
            'username' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $userId,
            'phone_num' => 'required|string|min:10|max:14|unique:admins,phone_num,' . $id,
            'password' => 'nullable|string|min:8|max:12',
        ];
    }
}
