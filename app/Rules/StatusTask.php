<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StatusTask implements Rule
{
    public function passes($attribute, $value)
    {
        return ($value == 'OPEN' || $value == 'IN PROGRESS' || $value == 'REVIEW' || $value == 'CLOSE');
    }

    public function message()
    {
        return 'The status is invalid.';
    }
}
