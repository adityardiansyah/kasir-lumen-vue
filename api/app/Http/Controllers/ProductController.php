<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalHelper;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public $user;
    public $store;

    public function __construct(Request $request) {
        $this->middleware('auth');
        $this->user = GlobalHelper::get_user($request->header('Authorization'));
        $this->store = GlobalHelper::get_store($request->header('Store'));
    }

    public function show($id='', Request $request)
    {
        try{
            $product = Product::where('store_id', $this->store->id)->latest();
            if(!empty($request->search)){
                $product = $product->where('name','LIKE','%'.$request->search.'%');
            }
            if($id){
                $product = $product->where('id',$id);
            }
            $product = $product->paginate(20);
            return GlobalHelper::return_response(true, 'Data ditemukan!', $product);
        }catch(\Exception $e){
            return GlobalHelper::return_response(false, 'Data tidak ditemukan!', $e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request, [
            'category_id' => 'required',
            'name' => 'required',
            'price_buy' => 'required',
            'price_sell' => 'required',
            'stock' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return GlobalHelper::return_response(false, 'Validated', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $path = '';
            if ($request->hasFile('image')) {
                $original_filename = $request->file('image')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './product/';
                $image = 'product-' . time() . '.' . $file_ext;

                if ($request->file('image')->move($destination_path, $image)) {
                    $path = '/product/' . $image;
                }
            }

            $data = Product::create([
                'store_id' => $this->store->id,
                'category_id' => $request->input('category_id'),
                'name' => $request->input('name'),
                'price_buy' => $request->input('price_buy'),
                'price_sell' => $request->input('price_sell'),
                'fee_reseller' => $request->input('fee_reseller'),
                'stock' => $request->input('stock'),
                'image' => $path
            ]);
            return GlobalHelper::return_response(true, 'Store Success Created', $data);
        } catch (\Throwable $th) {
            return GlobalHelper::return_response(false, 'Store Failed Created', $th->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}
