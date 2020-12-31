<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalHelper;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        try {
            $check = Cart::where('product_id', $request->input('product_id'))->where('store_id', $this->store->id)->first();
            $product = Product::where('id', $request->input('product_id'))->first();
            $product = $product->update([
                'stock' => $product->stock - 1
            ]);
            if(!$check){
                $data = Cart::create([
                    'store_id' => $this->store->id,
                    'product_id' => $request->input('product_id'),
                    'qty' => 1
                ]);
                return GlobalHelper::return_response(true, 'Cart Success Inserted', $data);
            }else{
                $check = $check->update([
                    'qty' => $check->qty + 1
                ]);
                return GlobalHelper::return_response(true, 'Cart Success Inserted', $check);
            }
        } catch (\Throwable $th) {
            return GlobalHelper::return_response(false, 'Cart Failed Inserted', $th->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request)
    {
        $check = Cart::where('id', $request->input('id'))->where('store_id', $this->store->id)->firstOrFail();
        $product = Product::where('id', $check->product_id)->firstOrFail();
        if ($request->input('type') == 'plus') {
            $product = $product->update([
                'stock' => $product->stock - 1
            ]);
            $check = $check->update([
                'qty' => $check->qty + 1
            ]);
            return GlobalHelper::return_response(true, 'Cart Success Updated', $check);
        } else {
            $product = $product->update([
                'stock' => $product->stock + 1
            ]);
            if($check->qty == 1){
                $check = $check->delete();
            }else{
                $check = $check->update([
                    'qty' => $check->qty - 1
                ]);
            }
            return GlobalHelper::return_response(true, 'Cart Success Updated', $check);
        }
    }

    public function show()
    {
        try {
            $data = Cart::select('carts.*','products.name','products.price_sell', 'products.fee_reseller', DB::raw('(products.price_sell * carts.qty) as sub_total'))
            ->leftJoin('products','products.id','carts.product_id')
            ->where('carts.store_id', $this->store->id)->get();
            return GlobalHelper::return_response(true, 'Get Cart Success', $data);
            
        } catch (\Throwable $th) {
            return GlobalHelper::return_response(false, 'Get Cart Failed', $th->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}
