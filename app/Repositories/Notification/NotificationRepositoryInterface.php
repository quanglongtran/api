<?php

namespace App\Repositories\Notification;

interface NotificationRepositoryInterface {
    /**
     * Update device token
     * 
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(array $data);

    /**
     * Send notification
     * 
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(array $data);
}