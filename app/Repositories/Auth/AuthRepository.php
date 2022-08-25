<?php

namespace App\Repositories\Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;


class AuthRepository implements AuthRepositoryInterface
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login($data): JsonResponse
    {
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()
            ], 422);
        }

        if (!$token = Auth::attempt($validator->validated())) {
            return response()->json([
                'success' => false,
                'message' => 'Sai tài khoản hoặc mật khẩu'
            ], 400);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register($data): JsonResponse
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($data['password'])]
        ));

        return response()->json([
            'success' => true,
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::logout();

        return response()->json(['message' => 'Đăng xuất thành công']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->createNewToken(Auth::refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile(): JsonResponse
    {
        if (!$user = Auth::user()) {
            return \response()->json([
                'success' => false,
                'message' => 'User not logged in!'
            ]);
        }
        return response()->json([
            'success' => true,
            'user' => Auth::user()
        ]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    public function changePassword($data): JsonResponse
    {
        $validator = Validator::make($data, [
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $userId = Auth::user()->getAuthIdentifier();

        $user = User::where('id', $userId)->update(
            ['password' => bcrypt($data['new_password'])]
        );

        return response()->json([
            'message' => 'User successfully changed password',
            'user' => $user,
        ], 201);
    }
}
