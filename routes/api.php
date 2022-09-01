<?php

use App\Http\Controllers\SocialController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ResetPasswordController;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use App\Repositories\Auth\AuthRepository;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('update', [AuthController::class, 'update']);
    Route::post('user-status', [AuthController::class, 'userStatus']);
    Route::post('delete', [AuthController::class, 'delete']);
    Route::get('find/{name}', [AuthController::class, 'find']);
    Route::post('/change-pass', [AuthController::class, 'changePassWord']);  

    Route::prefix('mail')->group(function() {
        Route::post('send', [MailController::class, 'sendEmail']);
        Route::get('all-template', [MailController::class, 'allTemplates']);
        Route::post('sync', [MailController::class, 'sync']);
    });  
});

Route::prefix('social')->name('social.')->group(function() {
    Route::post('sign-in/{provider}', [SocialController::class, 'signIn'])->name('index');
    Route::get('sign-in/{provider}/callback', [SocialController::class, 'callback'])->name('callback');
});

/*=== Email verification ===*/
Route::prefix('email/verify')->group(function() {
    Route::post('/', function(Request $request, AuthRepository $auth) {
        return $auth->verifyEmail($request->email);
    });

    Route::get('{id}/{token}', function ($id, $token, AuthRepository $auth) {
        return $auth->verifyEmailCallback($id, $token);
    })->middleware(['signed'])->name('verify-email');
});