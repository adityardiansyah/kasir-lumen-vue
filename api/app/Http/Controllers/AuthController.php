<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|max:225',
            'password' => 'required|min:6'
        ]);

        $user = User::where('email', $request->input('email'))->first();
        if(!$user){
            return GlobalHelper::return_response(false, 'Email not found!','',Response::HTTP_NOT_FOUND);
        }
        if(Hash::check($request->input('password'), $user->password)){
            $user->update(['api_token' => GlobalHelper::generateRandomString(80)]);
            return GlobalHelper::return_response(true, 'Login Success', $user);
        }else{
            return GlobalHelper::return_response(false, 'Login Failed, Password wrong!', '', Response::HTTP_NOT_FOUND);
        }
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:users|max:225',
            'password' => 'required|min:6',
            'name' => 'required|min:6',
            'phone' => 'required'
        ]);

        try{
            $data = User::create([
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'avatar' => '',
                'status' => 'Active'
            ]);
            return GlobalHelper::return_response(true, 'Success Register', $data);

        }catch(\Exception $e){
            return GlobalHelper::return_response(false, $e->getMessage(),'', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }

    public function return_response($status=true, $msg='', $data='', $code=200)
    {
        return response()->json([
            'message' => $msg,
            'success' => $status,
            'data' => $data
        ], $code);
    }
}