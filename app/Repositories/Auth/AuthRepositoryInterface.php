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
    public function login(array $data);

    /**
     * Registration
     * 
     * @param array
     * @return Illuminate\Http\JsonResponse
     */
    public function register(array $data);

    /**
     * Logout
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function logout();

    /**
     * Refresh JWT token
     */
    public function refresh();

    /**
     * Get current user session
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function userProfile();

    /**
     * Update user
     * 
     * @param array $data
     * @return JsonResponse
     */
    public function update(array $data);

    /**
     * Set user account status
     * 
     * @param array $data
     * @return JsonResponse
     */
    public function userStatus(array $data);

    /**
     * Delete an account
     * 
     * @return JsonResponse
     */
    public function delete();

    /**
     * Find user by name
     */
    public function find(array $data);

    /**
     * Change user password
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function changePassword(array $data);

    /**
     * Send verification email
     * 
     * @param $email
     * @return JsonResponse
     */
    public function verifyEmail($email);

    /**
     * Verify email
     * 
     * @param $id
     * @param $token
     * @return JsonResponse
     */
    public function verifyEmailCallback($id, $token);
}