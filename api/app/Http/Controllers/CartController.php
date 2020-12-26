<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalHelper;
use App\Models\Cart;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    public $user;
    public $store;

    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->user = GlobalHelper::get_user($request->header('Authorization'));
        $this->store = GlobalHelper::get_store($request->header('Store'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required',
            'qty' => 'required',
        ]);

        try {
            $check = Cart::where('product_id', $request->input('product_id'))->where('store_id', $this->user->id)->first();
            if(!$check){
                $data = Cart::create([
                    'store_id' => $this->user->id,
                    'product_id' => $request->input('product_id'),
                    'qty' => $request->input('qty')
                ]); 
                return GlobalHelper::return_response(true, 'Cart Success Inserted', $data);
            }else{
                $check = $check->update([
                    'qty' => $check->qty + $request->input('qty')
                ]);
                return GlobalHelper::return_response(true, 'Cart Success Inserted', $check);
            }
        } catch (\Throwable $th) {
            return GlobalHelper::return_response(false, 'Cart Failed Inserted', $th->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Type $var = null)
    {
        # code...
    }
}
