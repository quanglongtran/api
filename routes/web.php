<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
    return view('welcome');
});

Route::get('test', function(Request $request) {
    $query = explode('?', $_SERVER['REQUEST_URI'])[1];
    parse_str($query, $query);
    return $query;
    return parse_url('http://test.com/api/callback?code=4%2F0AdQt8qgUKXk3LEGtAOPwJ22xv6gpwSnI3381p7DlBCaHGSzxrPOHdkWFZrn3epvkkZ5q1A&scope=email+profile+openid+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email&authuser=0&prompt=consent');
});