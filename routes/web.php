<?php

use App\Mail\Mail as MailMail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\URL;
use App\Repositories\Mail\MailRepositoryInterface;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

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

Route::any('truncate/{table}', function($table) {
    DB::table($table)->truncate();
});

Route::any('clear', function() {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::any('bcrypt', function() {
    return bcrypt('123456');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', function(Request $request) {
    $accept = ['application/json'];
    return $request->wantsJson();
})->name('test');

Route::any('article/{id}/', function($id) {
    return Http::withHeaders([
        'Authorization' => "Bearer "
    ])->get(route('jwt'));
})->name('article')->middleware('signed');

Route::middleware('auth:api')->any('jwt', function() {
    
})->name('jwt');

// Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
