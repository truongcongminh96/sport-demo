<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function pendingOrders()
    {
        $orders = Order::where(['status' => 'Pending'])->orderBy('id', 'DESC')->get();
        return view('backend.orders.pending_orders', compact('orders'));
    }

    public function pendingOrderDetails($orderId)
    {
        $order = Order::with('province', 'district', 'ward', 'user')->where(['id' => $orderId])->first();
        $orderItem = OrderItem::with('product')->where(['order_id' => $orderId])->orderBy('id', 'DESC')->get();
        return view('backend.orders.pending_order_details', compact('order', 'orderItem'));
    }

    public function confirmedOrders()
    {
        $orders = Order::where(['status' => 'Confirmed'])->orderBy('id', 'DESC')->get();
        return view('backend.orders.confirmed_orders', compact('orders'));
    }

    public function processingOrders()
    {
        $orders = Order::where(['status' => 'Processing'])->orderBy('id', 'DESC')->get();
        return view('backend.orders.processing_orders', compact('orders'));
    }

    public function pickedOrders()
    {
        $orders = Order::where(['status' => 'Picked'])->orderBy('id', 'DESC')->get();
        return view('backend.orders.picked_orders', compact('orders'));
    }

    public function shippedOrders()
    {
        $orders = Order::where(['status' => 'Shipped'])->orderBy('id', 'DESC')->get();
        return view('backend.orders.shipped_orders', compact('orders'));
    }

    public function deliveredOrders()
    {
        $orders = Order::where(['status' => 'Delivered'])->orderBy('id', 'DESC')->get();
        return view('backend.orders.delivered_orders', compact('orders'));
    }

    public function cancelOrders()
    {
        $orders = Order::where(['status' => 'Cancel'])->orderBy('id', 'DESC')->get();
        return view('backend.orders.cancel_orders', compact('orders'));
    }

    public function pendingToConfirm($orderId)
    {
        Order::findOrFail($orderId)->update([
            'status' => 'Confirmed'
        ]);

        $notification = array(
            'message' => 'Order Confirm Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('pending-orders')->with($notification);
    }

    public function confirmToProcessing($orderId)
    {
        Order::findOrFail($orderId)->update([
            'status' => 'Processing'
        ]);

        $notification = array(
            'message' => 'Order Processing Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('confirmed-orders')->with($notification);
    }

    public function processingToPicked($orderId)
    {
        Order::findOrFail($orderId)->update([
            'status' => 'Picked'
        ]);

        $notification = array(
            'message' => 'Order Picked Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('processing-orders')->with($notification);
    }

    public function pickedToShipped($orderId)
    {
        Order::findOrFail($orderId)->update([
            'status' => 'Shipped'
        ]);

        $notification = array(
            'message' => 'Order Shipped Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('picked-orders')->with($notification);
    }
}
