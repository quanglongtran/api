<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Repositories\Auth\AuthRepository;

class SocialController extends Controller
{
    public $Auth;

    public function __construct(AuthRepository $auth)
    {
        $this->Auth = $auth;
    }
    
    public function signIn(string $provider)
    {
        try {
            $url = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
            
            return response()->json([
                'success' => true,
                'url' => $url
            ], 200);
        } catch (Exception $exception) {
            return \response()->json([
                'success' => false,
                'error' => $exception->getMessage()
            ]);
        }
    }

    public function callback(Request $request, string $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();

            $user = User::where('email', $socialUser->email)->first();
            if ($user) {
                return $this->Auth->login([
                    'email' => $user->email,
                    'password' => 123456,
                ]);
            }
            
            $user = User::create([
                'email' => $socialUser->email,
                'name' => $socialUser->name,
                'social_id' => $socialUser->id,
                'social_name' => $provider,
                'password' => \bcrypt('123456'),
            ]);

            return $this->Auth->login([
                'email' => $user->email,
                'password' => 123456,
            ]);
        } catch (Exception $exception) {
            return \response()->json([
                'success' => false,
                'error' => $exception,
                'message' => $exception->getMessage(),
            ], 400);
        }
    }
}
