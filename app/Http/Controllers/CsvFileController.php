<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UploadCsvFileRequest;
use App\Models\ImportedData;

class CsvFileController extends Controller
{
    public function uploadCsvFile(UploadCsvFileRequest $request) 
    {
        $upload = $request->file('csv_file');
        $lines = file($upload->path());
        $csv_data = [];

        foreach ($lines as $line) {
            $parsed_line = str_getcsv($line);
            
            $csv_data[] = $parsed_line;
        }

        $csv_header = array_shift($csv_data);
        $csv_header = $this->addTypes($csv_header, $csv_data);

        return view('csv.show', [
            'csv_header' => $csv_header,
            'csv_data' => $csv_data
        ]);
    }

    public function addColumn(Request $request)
    {
        $header = $request->input('csv_header');
        $body = $request->input('csv_body');


        $new_body = [];
        foreach ($body as $row) {
            $new_col = $row[0] . $row[2];
            if (preg_match("/^(\s|0)?$/", $new_col)) {
                $new_col = '[EMPTY]';
            }
            array_push($row, $new_col);
            array_push($new_body, $row);
        }

        $new_header = $header;
        $new_header[] = "New Column";
        $new_header = $this->addTypes($new_header, $new_body);

        return response()->json([
            'header' => $new_header,
            'body' => $new_body
        ]);
    }

    public function save(Request $request) {
        $header = $request->input('csv_header');
        $body = $request->input('csv_body');
        
        $csv_data = $body;
        array_unshift($csv_data, $header);

        $csv_data_json = json_encode($csv_data);

        $imported_data = new ImportedData();
        $imported_data->name = $request->input('name');
        $imported_data->data = $csv_data_json;
        $imported_data->save();

        return response()->json([
            'message' => 'Data has been saved'
        ]);
    }

    public function search(Request $request) {
        $query = $request->input('query');
        
        $imported_data = ImportedData::where('name', 'LIKE', '%'.$query.'%')
                            ->get();

        return response()->json([
            'imported_data' => $imported_data
        ]);
    }

    public function show($imported_data_id) {
        $imported_data = ImportedData::find($imported_data_id);

        $csv_data = json_decode($imported_data->data);
        $csv_header = array_shift($csv_data);

        return view('csv.show', [
            'csv_header' => $csv_header,
            'csv_data' => $csv_data
        ]);
    }

    /**
     * Adds the data types to header based on data contained in body
     */
    private function addTypes($header, $body) 
    {
        if ($header && $body) {
            $all_types = [];
            foreach ($body as $row) {
                for ($col_index = 0; $col_index < count($row); $col_index++) { 
                    
                    $current_value = $row[$col_index];
                    $current_type = "string";

                    if (is_numeric($current_value)) {
                        $current_type = "numeric";
                    }
                    $all_types[$col_index][] = $current_type;
                }
            }

            $types = [];
            foreach ($all_types as $col_all_types) {
                $types[] = array_reduce($col_all_types, function($carry, $item) {
                    if ($item !== "numeric") {
                        return "string";
                    }
                    return $carry;
                }, $col_all_types[0]);
            }

            // Let's update the header
            for ($i = 0; $i < count($header); $i++) { 
                $suffix = " (" . $types[$i] . ")";
                if (!(str_ends_with($header[$i], $suffix))) {
                    $header[$i] .= " (" . $types[$i] . ")";
                }
            }
            return $header;
        }
        return false;
    }
}
