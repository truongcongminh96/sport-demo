<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ward;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function stripeOrder(Request $request)
    {
        if (Session::has('coupon')) {
            $totalAmount = Session::get('coupon')['total_amount'];
        } else {
            $totalAmount = Cart::total();
        }

        $orderId = Order::insertGetId([
            'user_id' => Auth::id(),
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'ward_id' => $request->ward_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'post_code' => $request->post_code,
            'notes' => $request->notes,

            'payment_type' => 'Stripe',
            'payment_method' => 'Stripe',
            'payment_type' => 'COD',
            'transaction_id' => 'demo',
            'currency' => 'VND',
            'amount' => (int)$totalAmount,
            'order_number' => 'EOS' . mt_rand(10000000, 99999999),

            'invoice_no' => 'EOS' . mt_rand(10000000, 99999999),
            'order_date' => Carbon::now()->format('d F Y'),
            'order_month' => Carbon::now()->format('F'),
            'order_year' => Carbon::now()->format('Y'),
            'status' => 'Pending',
            'created_at' => Carbon::now(),
        ]);

        $carts = Cart::content();
        foreach ($carts as $cart) {
            OrderItem::insert([
                'order_id' => $orderId,
                'product_id' => $cart->id,
                'color' => $cart->options->color,
                'size' => $cart->options->size,
                'qty' => $cart->qty,
                'price' => $cart->price,
                'created_at' => Carbon::now()
            ]);
        }

        if (Session::has('coupon')) {
            Session::forget('coupon');
        }

        Cart::destroy();

        $notification = array(
            'message' => 'Success ordered',
            'alert-type' => 'success'
        );

        return redirect()->route('dashboard')->with($notification);
    }

    public function myOrders()
    {
        $orders = Order::where(['user_id' => Auth::id()])->orderBy('id', 'DESC')->get();
        return view('frontend.user.order.order_view', compact('orders'));
    }

    public function orderDetails($orderId)
    {
        $order = Order::with('province', 'district', 'ward', 'user')->where(['id' => $orderId, 'user_id' => Auth::id()])->first();
        $orderItem = OrderItem::with('product')->where(['order_id' => $orderId])->orderBy('id', 'DESC')->get();
        return view('frontend.user.order.order_view_details', compact('order', 'orderItem'));
    }
}
