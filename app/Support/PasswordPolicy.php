<?php

namespace App\Support;

class PasswordPolicy
{
    public static function rules(): array
    {
        return ['required', 'string', 'min:15', 'max:128', 'confirmed', function ($attribute, $value, $fail) {
            if (in_array(strtolower($value), [
                'passwordpassword', 'passwordpasswordpassword', '123456789012345',
                '12345678901234567890', 'qwertyuiopasdfgh', 'letmeinletmeinletmein',
            ], true)) $fail(__('auth-security.common_password'));
        }];
    }
}
