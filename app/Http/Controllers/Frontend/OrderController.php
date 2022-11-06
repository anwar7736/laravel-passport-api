<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Validator;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        
    }

    public function order_details($id)
    {
        return response()
            ->json([
                'order_summary' => Order::with('user')->findOrFail($id), 
                'order_details' => OrderDetail::with('category', 'product')
                                ->whereOrderId($id)
                                ->get()
                ]);
    }
}
