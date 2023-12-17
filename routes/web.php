<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CsvRecordController;

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

Route::get('/', function () {
    return view('welcome');
});


//User routes
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    //Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CSV Records Routes 
    Route::get('/csv-records', [CsvRecordController::class, 'index'])->name('csv-records.index');
    Route::post('/csv-records/import', [CsvRecordController::class, 'importCSV'])->name('csv-records.import');
    Route::delete('/csv-records/{file_name}/delete', [CsvRecordController::class, 'destroy'])->name('csv-records.delete');
});


//Admin routes
Route::middleware(['auth.admin'])->get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

// To test exceptions 
Route::get('/simulate-exception', function () {
    throw new Exception('This is a simulated exception!');
});

//The 404 page
Route::fallback(function () {
    return response()->view('404', [], 404);
});

require __DIR__.'/auth.php';
