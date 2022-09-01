<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public static function properties($data)
    {
        $users = is_array($data) ? $data : [$data];

        foreach ($users as $user) {
            $user->image = asset("storage/".imagePath()['user']['path']."/$user->image");
        }

        if (\count($users) == 1) {
            return $users[0];
        } else {
            return $users;
        }
    }
}
