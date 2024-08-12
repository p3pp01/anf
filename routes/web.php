<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PdfController;

use App\Http\Controllers\PdfLogController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/import-pdf', [PdfController::class, 'importPdf']);

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/import-pdf', [PdfController::class, 'importPdf']);

    Route::get('/import-multiple-pdfs', [PdfController::class, 'importMultiplePdfs']);

    Route::get('/logs', [PdfLogController::class, 'index'])->name('logs.index');


});
