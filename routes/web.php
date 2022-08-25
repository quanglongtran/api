<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    $token = Auth::attempt(['email' => 'tql0928159331@gmail.com', 'password' => 123456]);
    return $token;
});