<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Notification\NotificationRepositoryInterface as Notify;

class NotifyController extends Controller
{
    private string $serverKey = 'AAAA2hP_cos:APA91bH_44p_Mwmxaj4GB3bDwqW1c5cbds_lbyebGfSikJfC72qhhAtNBFLXJCkfFbR8wstJtKrOO2WhAeWu3kz26XnGpCw0kgI1e8K7sORbrwltldpyRfIRoyPZOSgHclNx_z5qHgW1';

    public $Notify;

    public function __construct(Notify $notify)
    {
        $this->middleware(['auth:api']);
        $this->Notify = $notify;
    }
    
    public function update(Request $request)
    {
        // User::find(Auth::user()->getAuthIdentifier())
        // ->update(['device_token' => $request->device_token]);

        // return \response()->json([
        //     'success' => true,
        //     'message' => 'Lưu mã thiết bi thành công'
        // ]);
        return $this->Notify->update($request->all());
    }

    public function send(Request $request)
    {
        // $url = 'https://fcm.googleapis.com/fcm/send';

        // $FcmToken = User::whereNotNull('device_token')->pluck('device_token')->all();
            
        // $serverKey = $this->serverKey;

        // $data = [
        //     "registration_ids" => $FcmToken,
        //     "notification" => [
        //         "title" => $request->title,
        //         "body" => $request->body,  
        //     ]
        // ];
        // $encodedData = json_encode($data);
        // $headers = [
        //     'Authorization:key=' . $serverKey,
        //     'Content-Type: application/json',
        // ];

        // $ch = curl_init();
        
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // // Disabling SSL Certificate support temporarly
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

        // $result = curl_exec($ch);
        // if ($result === FALSE) {
        //     die('Curl failed: ' . curl_error($ch));
        // }    
        // curl_close($ch);

        // return $result;
        return $this->Notify->send($request->all());
    }
}
