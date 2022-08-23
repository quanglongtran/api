<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    public function getGoogleSignInUrl()
    {
        try {
            $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
            
            return response()->json(['url' => $url])->setStatusCode(Response::HTTP_OK);
        } catch (Exception $exception) {
            return $exception;
        }
    }

    public function loginCallback(Request $request)
    {
        $query = explode('?', $_SERVER['REQUEST_URI'])[1];
        parse_str($query, $query);
        return $query;

        try {
            $state = $request->input('state');

            \parse_str($state, $result);
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('email', $googleUser->email)->first();
            if ($user) {
                throw new Exception(__('google sign in email existed'));
            }
            $user = User::create([
                'email' => $googleUser->email,
                'name' => $googleUser->name,
                'google_id' => $googleUser->google_id,
                'password' => '123456',
            ]);

            return response()->json([
                'status' => __('google sign in successful'),
                'data' => $user,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return \response()->json([
                'status' => __('google sign in failed'),
                'error' => $exception,
                'message' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
