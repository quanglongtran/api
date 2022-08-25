<?php

namespace App\Repositories\Auth;

use Illuminate\Http\JsonResponse;

interface AuthRepositoryInterface {
    /**
     * Login
     * 
     * @param array
     * @return Illuminate\Http\JsonResponse
     */
    public function login(array $data): JsonResponse;

    /**
     * Registration
     * 
     * @param array
     * @return Illuminate\Http\JsonResponse
     */
    public function register(array $data): JsonResponse;

    /**
     * Logout
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse;

    /**
     * Refresh JWT token
     */
    public function refresh(): JsonResponse;

    /**
     * Get current user session
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function userProfile(): JsonResponse;

    /**
     * Change user password
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function changePassword(array $data): JsonResponse;
}