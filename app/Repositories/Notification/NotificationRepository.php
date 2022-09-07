<?php

namespace App\Repositories\Notification;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationRepository implements NotificationRepositoryInterface {
    private string $serverKey = 'AAAA2hP_cos:APA91bH_44p_Mwmxaj4GB3bDwqW1c5cbds_lbyebGfSikJfC72qhhAtNBFLXJCkfFbR8wstJtKrOO2WhAeWu3kz26XnGpCw0kgI1e8K7sORbrwltldpyRfIRoyPZOSgHclNx_z5qHgW1';
    
    public function update($data)
    {
        User::find(Auth::user()->getAuthIdentifier())
        ->update(['device_token' => $data['device_token']]);

        return \response()->json([
            'success' => true,
            'message' => 'Lưu mã thiết bi thành công'
        ]);
    }

    public function send($data)
    {
        $validator = Validator::make($data, [
            'title' => 'required',
            'body' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()
            ]);
        }
        
        $url = 'https://fcm.googleapis.com/fcm/send';

        $FcmToken = User::whereNotNull('device_token')->pluck('device_token')->all();
            
        $serverKey = $this->serverKey;

        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $data['title'],
                "body" => $data['body'],  
            ]
        ];
        $encodedData = json_encode($data);
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }    
        curl_close($ch);

        return \response()->json([
            'success' => true,
            'message' => 'Gửi thông báo thành công',
            'result' => $result,
        ]);
    }
}