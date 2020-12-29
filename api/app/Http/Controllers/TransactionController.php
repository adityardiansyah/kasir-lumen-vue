<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalHelper;
use App\Models\DetailOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
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
        // return $request->customer_id;
        // $validator = Validator::make($request, [
        //     'payment' => 'numeric'
        // ]);
        // if ($validator->fails()) {
        //     return GlobalHelper::return_response(false, 'Validated', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        // }
        try {
            $data = Order::create([
                'store_id' => $this->store->id,
                'customer_id' => $request->customer_id,
                'invoice' => $request->invoice,
                'total' => $request->total
            ]);
            DetailOrder::create([
                'order_id' => $data->id,
                'list_order' => $request->list_data
            ]);
            return GlobalHelper::return_response(true, 'Order success inserted', $data);
        } catch (\Throwable $th) {
            return GlobalHelper::return_response(false, 'Order failed inserted', $th->getMessage(), Response::HTTP_NOT_IMPLEMENTED);
        }
    }
}
