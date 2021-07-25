<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CsvRowsCount implements Rule
{
    private $min;
    private $max;
    
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($min, $max = NULL)
    {
        $this->min = $min;
        $this->max = $max;
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
        $uploadedFile = $value;

        $lines = file($uploadedFile->path());

        if (count($lines) < $this->min) {
            return false;
        }

        if (is_int($this->max) && count($lines) > $this->max) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $message = 'The CSV file needs to have at least ' . $this->min . ' rows';
        
        if (is_int($this->max)) {
            $message .= ', but no more than ' . $this->max . ' rows';
        }
        
        $message .= '.';

        return $message;
    }
}
