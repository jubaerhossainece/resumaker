<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxArrayLength implements Rule
{
    private $length;
    private $attribute;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($length)
    {
        $this->length = $length;
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
        $this->attribute = $attribute;
        return count($value) <= $this->length ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The $this->attribute length can not be greater than $this->length.";
    }
}
