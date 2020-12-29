<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalHelper;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    public $user;

    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->user = GlobalHelper::get_user($request->header('Authorization'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request, [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return GlobalHelper::return_response(false, 'Validated', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $data = Store::create([
                'user_id' => $this->user->id, 
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'token' => GlobalHelper::generateRandomString(40)
            ]);
            return GlobalHelper::return_response(true, 'Store Success Created', $data);
        } catch (\Throwable $th) {
            return GlobalHelper::return_response(false, 'Store Failed Created', $th->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function create_token(Request $request)
    {
        $check = Store::where('user_id', $this->user->id)->where('id', $request->input('id'))->first();
        if($check){
            $check->token = GlobalHelper::generateRandomString(40);
            $check->save();
            return GlobalHelper::return_response(true, 'Store Found, Token Updated!', $check);
        }else{
            return GlobalHelper::return_response(false, 'Store Not Found!', '', Response::HTTP_NOT_FOUND);
        }
    }

    public function get_store(Request $request)
    {
        $store = Store::where('user_id', $this->user->id)->get();
        if(!empty($store)){
            return GlobalHelper::return_response(true, 'Store Found', $store);
        }else{
            return GlobalHelper::return_response(false, 'Store Not Found!', '', Response::HTTP_NOT_FOUND);
        }
    }
}
