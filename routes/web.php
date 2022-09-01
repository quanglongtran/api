<?php

use App\Http\Controllers\ImageController;
use App\Mail\Mail as SendMail;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserController;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

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
    $collection = collect([1, 2, 3, 4, 5]);

    dd($collection->pop(), $collection->all());
})->name('test');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <b>Result: </b>
</body>

</html>