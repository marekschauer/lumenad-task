<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CsvColumnsCount implements Rule
{
    private $min;
    private $max;
    private $count_min;
    private $count_max;
    
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
        $this->count_min = PHP_INT_MAX;
        $this->count_max = PHP_INT_MIN;
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

            $this->count_min = min([$cols_count, $this->count_min]);
            $this->count_max = max([$cols_count, $this->count_max]);
            
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
        $message = 'The CSV file needs to have at least ' . $this->min . ' columns, but no more than ' . $this->max . ' columns.';
        $current_count = $this->count_min === $this->count_max ? $this->count_min : $this->count_min . ' - ' . $this->count_max;
        $message .= 'Uploaded file has ' . $current_count . 'columns';
        return $message;
    }
}
