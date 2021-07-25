<?php

namespace App\Http\Requests;

use App\Rules\CsvRowsCount;
use App\Rules\CsvColumnsCount;
use Illuminate\Foundation\Http\FormRequest;

class UploadCsvFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'csv_file' => [
                new CsvRowsCount(2),
                new CsvColumnsCount(3, 10)
            ],
        ];
    }
}
