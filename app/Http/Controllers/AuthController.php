<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Auth\AuthRepository;


class AuthController extends Controller
{
    public $Auth;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(AuthRepository $auth)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->Auth = $auth;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        return $this->Auth->login($request->all());
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        return $this->Auth->register($request->all());
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        return $this->Auth->logout();
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(Auth::refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return $this->Auth->userProfile();
    }

    public function changePassWord(Request $request)
    {
        return $this->Auth->changePassword($request->all());
    }
}
