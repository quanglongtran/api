<?php

use App\Exports\ExportFile;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\pdfController;
use App\Mail\Mail as SendMail;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ExportController as Export;
use App\Http\Controllers\NotifyController;

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

Route::any('truncate/{table}', function ($table) {
    DB::table($table)->truncate();
});

Route::any('clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::any('bcrypt', function () {
    return bcrypt('123456');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', function (Request $request) {
    return User::whereNotNull('device_token')->pluck('device_token')->all();
})->name('test');

Route::prefix('export')->name('export.')->group(function() {
    Route::get('pdf', [Export::class, 'pdf']);
    // Route::get('sheet', [Export::class, 'sheet'])->name('sheet');
    Route::get('multi-sheet', [Export::class, 'multiSheet'])->name('multi-sheet');
    Route::view('view', 'export.user', ['users' => User::all()]);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
?>
