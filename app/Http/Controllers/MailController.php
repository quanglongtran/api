<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendMail;
use App\Models\MailTemplate;
use Illuminate\Support\Facades\DB;

class MailController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }
    
    public function sendEmail(Request $request)
    {
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

    public function allTemplates()
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

    public function sync()
    {
        $data = $this->template();
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

    public function template()
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
