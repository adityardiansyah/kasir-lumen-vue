<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalHelper;
use App\Models\Cart;
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
                'total' => $request->total,
                'fee_reseller' => $request->fee_reseller,
                'payment' => $request->payment,
                'cashback' => $request->cashback,
            ]);
            DetailOrder::create([
                'order_id' => $data->id,
                'list_order' => $request->list_data
            ]);

            Cart::where('store_id', $this->store->id)->delete();
            return GlobalHelper::return_response(true, 'Order success inserted', $data);
        } catch (\Throwable $th) {
            return GlobalHelper::return_response(false, 'Order failed inserted', $th->getMessage(), Response::HTTP_NOT_IMPLEMENTED);
        }
    }

    public function show($invoice)
    {
        try {
            $data = Order::select('orders.*', 'order_details.list_order')
            ->leftJoin('order_details', 'order_details.order_id', 'orders.id')
            ->where('invoice',$invoice)
            ->first();
            $data->time = date('d-m-Y H:i:s', strtotime($data->created_at));
            return GlobalHelper::return_response(true, 'Get Order success', $data);

        } catch (\Throwable $th) {
            return GlobalHelper::return_response(false, 'Get Order failed', $th->getMessage(), Response::HTTP_NOT_IMPLEMENTED);
        }
    }
}
