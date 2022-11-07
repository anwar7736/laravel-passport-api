<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Validator, Auth;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $user_id    = Auth::id();
        $invoice_no = "INV".rand(11111111,99999999);
        $amount = 0;
        $charge = 50;
        $discount = 0;
        $total  = 0;
        $data['user_id'] = $user_id;
        $data['invoice_no'] = $invoice_no;
        $data['amount'] = $amount;
        $data['charge'] = $charge;
        $data['discount'] = $discount;
        $data['total'] = $total;
        $order = Order::create($data);
        
        foreach($request->all() as $product)
        {
            $amount += $product['price'] * $product['quantity'];
            $discount += $product['discount'];
            $data['order_id'] = $order->id;
            $data['category_id'] = $product['category_id'];
            $data['product_id'] = $product['id'];
            $data['quantity'] = $product['quantity'];
            $data['price'] = $product['price'];
            $data['discount'] = $product['discount'];
            $data['total'] = ($product['quantity'] * $product['price']) - $product['discount']; 
            OrderDetail::create($data);
        }
        $order->amount = $amount;
        $order->discount = $discount;
        $order->total = ($amount + $charge) - $discount;
        $order->save();

        return "Order has been successfully placed!";



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
