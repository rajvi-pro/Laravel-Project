<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Carbon\Carbon;

class ValidBirthDate implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $date = Carbon::parse($value);
            
            // Check if date is in the future
            if ($date->isFuture()) {
                $fail('Date of birth cannot be in the future.');
            }
            
            // Check if person is more than 150 years old
            if ($date->diffInYears(now()) > 150) {
                $fail('Date of birth must be within the last 150 years.');
            }
            
            // Check if person is less than 0 years old (future date caught above, but extra safety)
            if ($date->isFuture()) {
                $fail('Date of birth must be in the past.');
            }
            
        } catch (\Exception $e) {
            $fail('Invalid date format for date of birth.');
        }
    }
}
