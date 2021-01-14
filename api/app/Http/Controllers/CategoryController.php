<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalHelper;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public $user;
    public $store;

    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->user = GlobalHelper::get_user($request->header('Authorization'));
        $this->store = GlobalHelper::get_store($request->header('Store'));
    }

    public function index()
    {
        try {
            $data = Category::where('store_id', $this->store->id)->get();
            return GlobalHelper::return_response(true, 'Get Category Success', $data);
        } catch (\Throwable $th) {
            return GlobalHelper::return_response(false, 'Category Failed Created', $th->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        try {
            $data = Category::create([
                'store_id' => $this->store->id,
                'name' => $request->input('name'),
                'description' => $request->input('description')
            ]);
            return GlobalHelper::return_response(true, 'Category Success Created', $data);
        } catch (\Throwable $th) {
            return GlobalHelper::return_response(false, 'Category Failed Created', $th->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy($id)
    {
        if($id){
            $check_product = Product::where('category_id', $id)->first();
            if(empty($check_product)){
                Category::find($id)->delete();
                return GlobalHelper::return_response(true, 'Category Success Deleted', '');
            }else{
                return GlobalHelper::return_response(false, 'Category Failed Deleted, there is a category in the product', '', Response::HTTP_NOT_FOUND);
            }
        }else{
            return GlobalHelper::return_response(false, 'Category Not Found', '', Response::HTTP_NOT_FOUND);
        }
    }
}
