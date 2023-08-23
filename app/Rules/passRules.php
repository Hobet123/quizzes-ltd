<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CustomPasswordRule implements Rule
{
    public function passes($attribute, $value)
    {
        // Check for password length between 8 and 16 characters
        if (strlen($value) < 8 || strlen($value) > 16) {
            return false;
        }

        // Check for at least one uppercase letter
        if (!preg_match('/[A-Z]/', $value)) {
            return false;
        }

        // Check for at least one digit
        if (!preg_match('/\d/', $value)) {
            return false;
        }

        // Check for at least one special character
        if (!preg_match('/[^A-Za-z0-9]/', $value)) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'The attribute must be between 8 and 16 characters, contain at least one uppercase letter, one digit, and one special character.';
    }
}
