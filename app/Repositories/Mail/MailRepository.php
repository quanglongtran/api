<?php

namespace App\Repositories\Mail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendMail;
use App\Models\MailTemplate;
use Illuminate\Support\Facades\DB;

class MailRepository implements MailRepositoryInterface
{   
    // public static function sendEmail(Request $request)
    public static function sendEmail(array $data = [])
    {
        $request = new Request();
        
        $validator = Validator::make($request->all(), [
            'mail_to' => 'required',
            'title' => 'required',
            'template' => 'required'
        ]);

        if (!file_exists(resource_path("views/mail/$request->template.blade.php"))) {
            return \response()->json([
                'success' => \false,
                'message' => 'Không tìm thấy mẫu email này'
            ]);
        }

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

    public static function allTemplates()
    {
        $request = \request();
        
        $validator = Validator::make($request->all(), [
            'page' => 'integer',
            'per_page' => 'integer',
        ]);

        if ($validator->fails()) {
            return \response()->json([
                'success' => false,
                'error' => $validator->errors()
            ]);
        }
        
        $request->perPage = (int) $request->per_page ?? 10;
        
        $items = MailTemplate::all('name');
        
        return \response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    public static function sync()
    {
        $data = self::template();
        $result = [];
        
        MailTemplate::truncate();
        
        foreach ($data as $item) {
            $result[] = [
                'name' => $item,
                'created_at' => \now('Asia/Ho_Chi_Minh'),
                'updated_at' => \now('Asia/Ho_Chi_Minh'),
            ];
        }
        
        MailTemplate::insert($result);

        return \response()->json([
            'success' => true,
            'message' => 'Đồng bộ mẫu email thành công'
        ]);
    }

    public static function template()
    {
        $items = [];
        if ($handle = opendir(resource_path('views/mail'))) {

            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $items[] = explode('.blade.php', $entry)[0];
                }
            }
        
            closedir($handle);
        }

        return $items;
    }
}
