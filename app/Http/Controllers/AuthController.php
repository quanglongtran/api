<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Support\Facades\Response;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public $Auth;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(AuthRepository $auth)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->Auth = $auth;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        return $this->Auth->login($request->all());
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        return $this->Auth->register($request->all());
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        return $this->Auth->logout();
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(Auth::refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return $this->Auth->userProfile();
    }

    public function update(Request $request)
    {
        return $this->Auth->update($request->all());
    }

    public function userStatus(Request $request)
    {
        return $this->Auth->userStatus($request->all());
    }

    public function delete()
    {
        return $this->Auth->delete();
    }

    public function find($name)
    {
        return $this->Auth->find(['name' => $name]);
    }

    public function changePassWord(Request $request)
    {
        return $this->Auth->changePassword($request->all());
    }

    public function forgot_password(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'email' => "required|email",
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                $response = Password::sendResetLink($request->only('email'), function (Message $message) {
                    $message->subject($this->getEmailSubject());
                });
                switch ($response) {
                    case Password::RESET_LINK_SENT:
                        return Response::json(array("status" => 200, "message" => trans($response), "data" => array()));
                    case Password::INVALID_USER:
                        return Response::json(array("status" => 400, "message" => trans($response), "data" => array()));
                }
            } catch (\Swift_TransportException $ex) {
                $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
            } catch (\Exception $ex) {
                $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
            }
        }
        return Response::json($arr);
    }

    public function change_password(Request $request)
    {
        $input = $request->all();
        $userid = Auth::guard('api')->user()->id;
        $rules = array(
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                    $arr = array("status" => 400, "message" => "Check your old password.", "data" => array());
                } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                    $arr = array("status" => 400, "message" => "Please enter a password which is not similar then current password.", "data" => array());
                } else {
                    User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                    $arr = array("status" => 200, "message" => "Password updated successfully.", "data" => array());
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                $arr = array("status" => 400, "message" => $msg, "data" => array());
            }
        }
        return Response::json($arr);
    }
}
