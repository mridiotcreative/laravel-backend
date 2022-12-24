<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Hash;

class MatchCustomerOldPassword implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $password = null;
    public function __construct()
    {
        if (auth()->guard('customer')->check()) {
            $this->password = auth()->guard('customer')->user()->password;
        } else {
            $this->password = auth()->guard('api')->user()->password;
        }
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Hash::check($value, $this->password);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Current password must match with old password';
    }
}
