<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GroupTask implements Rule
{
    public function passes($attribute, $value)
    {
        return ($value == 'status' || $value == 'deadline');
    }

    public function message()
    {
        return 'The group type is invalid.';
    }
}
