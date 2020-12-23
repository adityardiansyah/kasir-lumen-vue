<?php

namespace App\Helpers;

use App\Models\Store;
use App\Models\User;

class GlobalHelper
{
    public static function return_response($status = true, $msg = '', $data = '', $code = 200)
    {
        return response()->json([
            'message' => $msg,
            'success' => $status,
            'data' => $data
        ], $code);
    }

    public static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function get_user($user)
    {
        $auth = explode(' ', $user);
        return User::where('api_token', $auth[1])->firstOrFail();
    }

    public static function get_store($store)
    {
        return Store::where('token', $store)->firstOrFail();
    }
}
