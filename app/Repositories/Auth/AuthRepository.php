<?php

namespace App\Repositories\Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Mail\MailRepositoryInterface;
use Illuminate\Support\Facades\URL;
use App\Mail\Mail as SendMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AuthRepository implements AuthRepositoryInterface
{
    public MailRepositoryInterface $Mail;

    public function __construct(MailRePositoryInterface $mail)
    {
        $this->Mail = $mail;
    }
    
    public function login($data)
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

    public function register($data)
    {
        // return Str::random(100);
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

        $token = Auth::attempt(['email' => $data['email'], 'password' => $data['password']]);

        $this->verifyEmail($user->email);

        return $this->createNewToken($token, 'Đăng ký thành công', 201);
    }

    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Đăng xuất thành công']);
    }

    public function refresh()
    {
        return $this->createNewToken(Auth::refresh());
    }

    public function userProfile()
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

    public function update($data)
    {
        $validator = Validator::make($data, [
            'name' => 'string|min:4',
            'status' => 'integer|max:1',
            'image' => 'file|mimes:png,jpg'
        ]);

        if ($validator->fails()) {
            return \response()->json([
                'success' => false,
                'error' => $validator->errors()
            ]);
        }

        if (isset($data['image'])) {
            $insert = \array_merge($validator->validated(), [
                'image' => $data['image']->getClientOriginalName(),
            ]);

            uploadImage($data, \imagePath(1)['user']);
        } else {
            $insert = $validator->validated();
        }
        
        $user = User::find(Auth::user()->getAuthIdentifier());
        $user->update($insert);

        return \response()->json([
            'success' => true,
            'message' => 'Cập nhật thông tin người dùng thành công',
            'user' => $user
        ]);
    }

    public function userStatus($data)
    {
        if ($data['status'] == 1 || $data['status'] == 'true' || $data['status'] == 'active') {
            $status = 1;
        } else if ($data['status'] == 0 || $data['status'] == 'false' || $data['status'] == 'deactive') {
            $status = 0;
        } else {
            $status = null;
        }

        if (is_null($status)) {
            return \response()->json([
                'success' => false,
                'error' => ['status' => 'Giá trị không hợp lệ']
            ]);
        }

        return $this->update(['status' => $status]);
    }

    public function delete()
    {
        User::find(Auth::user()->getAuthIdentifier())->delete();
        
        return \response()->json([
            'success' => true,
            'message' => 'Đã xóa tài khoản người dùng thành công'
        ]);
    }

    public function find(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return \response()->json([
                'success' => false,
                'error' => $validator->errors()
            ]);
        }

        $users = DB::table('users')->where('status', 1)->where('name', 'like', "%{$data['name']}%")->get();

        return \response()->json([
            'success' => \true,
            'users' => $users
        ]);
    }

    protected function createNewToken($token, string $message = '', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => auth()->user()
        ], $code);
    }

    public function changePassword($data)
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

    public function verifyEmail($email)
    {
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return \response()->json([
                'success' => \false,
                'error' => $validator->errors()
            ]);
        }

        $user = User::whereEmail($email)->first();
        
        if ($user->hasVerifiedEmail()) {
            return \response()->json([
                'success' => false,
                'message' => "Email này đã được xác thực vào $user->email_verified_at (GMT+7)"
            ]);
        }

        $url = URL::signedRoute("api.verify-email", ['id' => $user->id, 'token' => \base64_encode($user->email)], now()->addMinutes(10));
        $mail = new SendMail([
            'title' => 'Vui lòng xác nhận địa chỉ email',
            'template' => 'verify',
            'data' => ['url' => $url]
        ]);

        Mail::to($user->email)->queue($mail);

        return \response()->json([
            'success' => true,
            'message' => 'Đã gửi xác thực email thành công',
        ]);
    }

    public function verifyEmailCallback($id, $token)
    {
        $user = User::find($id);

        if (!$user->hasVerifiedEmail()) {
            User::find($id)->markEmailAsVerified();
        }

        return response()->json([
            'success' => true,
            'message' => 'Xác thực email thành công'
        ]);
    }
}
