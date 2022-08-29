<?php

use App\Http\Controllers\SocialController;
use App\Http\Controllers\AuthController;
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

Route::get('email/verify/{id}/{token}', function ($id, $token) {
    $exp = JWTAuth::parseToken($token)->getPayload()['exp'];
    $user = User::find($id);
    
    $success = $exp - time() >= 0 ? true : false;
    $message = $exp - time() >= 0 ? 'Xác thực email thành công' : 'Token đã hết hạn';

    if ($success && !$user->hasVerifiedEmail()) {
        User::find($id)->markEmailAsVerified();
    }

    return response()->json([
        'success' => $success,
        'message' => $message
    ]);
})->middleware(['signed'])->name('verify-email');

Route::post('reset-password', [ResetPasswordController::class, 'sendMail']);
Route::put('reset-password/{token}', [ResetPasswordController::class, 'reset']);

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
 
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');