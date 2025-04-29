<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PasswordCheckHelper
{
    /**
     * Handle password confirmation and hashing.
     *
     * @param string|null $checkPassword
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public static function handlePassword($password, $checkPassword)
    {
        if ($checkPassword !== $password) {
            throw ValidationException::withMessages([
                'password' => 'Password tidak konsisten!',
            ]);
        } else {
            $password = Hash::make($password);
        }

        return $password;
    }
}
