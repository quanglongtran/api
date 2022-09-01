<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

function mail_path(string $path = '')
{
    $path = $path[0] == '/' ? substr($path, 1) : $path;
    return resource_path("views\\mail\\$path");
}

if (!function_exists('getPrivateKey')) {
    function getPrivateKey()
    {
        $privateKey = 'w8d7WR0w7odDRdJq9H0RBiKmLJ7EhPdOvydsnKQgv7eLl82GxylR0iZMAQ8JE6d0q6hFrv6eVTH8xp85YLpFqOfKW8TeRmHvkjnn';
        $base64 = base64_encode($privateKey);
        return [substr($base64, 0, -4) . 'gb==', $base64];
    }
}

if (!function_exists('hasValidPrivateKey')) {
    function hasValidPrivateKey($private_key)
    {
        return substr($private_key, 0, -4) . 'bg==';
        if (substr($private_key, 1)) {
        }
    }
}

if (!function_exists('imagePath')) {
    function imagePath($id = '')
    {
        if ($id != '') {
            $id = ':' . $id;
        }
        $data['user'] = [
            'path' => 'images/user',
            'size' => '300x300',
            'database' => "users$id"
        ];
        return $data;
    }
}

function uploadImage(array $data, array $path_size)
{
    $data = array_merge($data, $path_size);

    $validator = Validator::make($data, [
        'image' => 'required|file|mimes:png,jpg',
        'path' => 'required',
        'size' => 'required',
        'database' => 'required',
    ]);

    if ($validator->fails()) {
        return \response()->json([
            'success' => \false,
            'error' => $validator->errors(),
        ]);
    }
    
    $image = $data['image'];
    
    $name = explode('.', $image->getClientOriginalName());
    $name = implode(',', array_splice($name, 0, -1));

    $filename = $image->hashName();

    $width = (int) explode('x', $data['size'])[0];
    $height = (int) explode('x', $data['size'])[1];

    $image_resize = Image::make($image->getRealPath());
    $image_resize->fit($width, $height);

    if (!file_exists(public_path('storage'))) {
        Artisan::call('storage:link');
    }

    if (!file_exists(public_path("storage/{$data['path']}"))) {
        mkdir(public_path($data['path']), 0777, true);
    }
    
    $db = explode(':', $data['database']);
    $table = $db[0];

    if (count($db) > 1 && count($db) == 2) {
        $stored_image = DB::table($table)->where('id', $db[1]);
        
        Storage::delete("public/{$data['path']}/{$stored_image->first('image')->image}");
        
        $stored_image->update(['image' => $filename]);
        $image_resize->save(public_path("storage/{$data['path']}/$filename"));
    
        return true;
    }

    return false;
}

function slug($title = '', $seperator = '-') {
    return Str::slug($title);
}