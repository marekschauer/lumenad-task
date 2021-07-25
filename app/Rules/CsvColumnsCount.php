<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CsvColumnsCount implements Rule
{
    private $min;
    private $max;
    
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($min, $max)
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

        foreach ($lines as $line) {
            $parsed_line = str_getcsv($line);
            $cols_count = count($parsed_line);
            
            if ($cols_count < $this->min) {
                return false;
            }

            if ($cols_count > $this->max) {
                return false;
            }
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
        return 'The CSV file needs to have at least ' . $this->min . ' columns, but no more than ' . $this->max . ' columns.';
    }
}
