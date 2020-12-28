<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalHelper;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:225|email:rfc,dns',
            'password' => 'required|min:6'
        ], [
            'email.required' => 'Email is not empty!',
            'email.email' => 'Insert your real email!',
            'password.required' => 'Password is not empty!',
            'password.min' => 'Insert min 6 character !',
        ]);
        if ($validator->fails()) {
            return GlobalHelper::return_response(false, 'Validated', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::where('email', $request->input('email'))->first();
        if(!$user){
            return GlobalHelper::return_response(false, 'Email not found!','',Response::HTTP_NOT_FOUND);
        }
        if(Hash::check($request->input('password'), $user->password)){
            $user->update(['api_token' => GlobalHelper::generateRandomString(80)]);
            $user->haveStore = $this->checkStore($user->id);
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

    public function checkStore($user)
    {
        $store = Store::where('user_id', $user)->first();
        if(empty($store)){
            $data = 'FALSE';
        }else{
            $data = 'TRUE';
        }
        return $data;
    }

    public function logout(Request $request)
    {
        $token_user = GlobalHelper::get_user($request->header('Authorization'));
        $token_store = GlobalHelper::get_store($request->header('Store'));

        $user = User::where('api_token', $token_user->api_token)->first();
        $store = Store::where('token', $token_store->token)->first();

        if(!empty($user) && !empty($store)){
            $user->api_token = null;
            $user->save();
            $store->token = null;
            $store->save();

            return GlobalHelper::return_response(true, 'Logout Success!', '');
        }else{
            return GlobalHelper::return_response(false, 'Logout Failed!','', Response::HTTP_UNAUTHORIZED);
        }
    }
}
