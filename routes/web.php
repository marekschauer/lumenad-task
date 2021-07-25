<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CsvFileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('csv.index');
});

Route::post('/csv-upload', [CsvFileController::class, 'uploadCsvFile'])->name('csv.upload');
Route::post('/csv/add-column', [CsvFileController::class, 'addColumn'])->name('csv.add_column');
Route::post('/csv/save', [CsvFileController::class, 'save'])->name('csv.save');
Route::get('/csv/search', [CsvFileController::class, 'search'])->name('csv.search');
Route::get('/csv/{imported_data_id}', [CsvFileController::class, 'show'])->name('csv.search');
// TODO: deleting
