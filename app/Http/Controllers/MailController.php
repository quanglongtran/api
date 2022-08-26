<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendMail;

class MailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mail_to' => 'required',
            'title' => 'required'
        ]);

        if ($validator->fails()) {
            return \response()->json([
                'success' => false,
                'error' => $validator->errors()
            ]);
        }

        SendMail::dispatch($request->all());
        
        return \response()->json([
            'success' => true,
            'message' => 'Gửi email thành công'
        ]);
    }
}
