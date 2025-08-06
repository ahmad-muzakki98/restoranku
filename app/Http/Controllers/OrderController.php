<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        // Fetch all orders from the database
        $orders = Order::all()->sortByDesc('created_at');

        // Return the view with the orders
        return view('admin.order.index', compact('orders'));
    }

    public function show($id)
    {
        // Fetch the order by ID
        $order = Order::findOrFail($id);
        $orderItems = OrderItem::where('order_id', $order->id)->get();

        // Return the view with the order details
        return view('admin.order.show', compact('order', 'orderItems'));
    }

    public function updateStatus($id)
    {
        // Fetch the order by ID
        $order = Order::findOrFail($id);

        // Update the order status to 'settled'
        if (Auth::user()->role->role_name == 'admin' || Auth::user()->role->role_name == 'cashier') {
            $order->status = 'settlement';
        } else {
            $order->status = 'cooked';
        }

        // Update the order status to 'settlement'
        // $order->status = 'settlement';
        $order->save();

        // Redirect back to the orders index with a success message
        return redirect()->route('orders.index')->with('success', 'Order settled successfully.');
    }

    // public function cooked($id)
    // {
    //     // Fetch the order by ID
    //     $order = Order::findOrFail($id);

    //     // Update the order status to 'cooked'
    //     $order->status = 'cooked';
    //     $order->save();

    //     // Redirect back to the orders index with a success message
    //     return redirect()->route('orders.index')->with('success', 'Order cooked successfully.');
    // }
}
