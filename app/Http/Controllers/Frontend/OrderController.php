<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('frontend.order-show', compact('order'));
    }


    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product', 'payment');

        return view('frontend.order-success', compact('order'));
    }


    public function index()
    {
        $orders = Order::where(
            'user_id',
            auth()->id()
        )
        ->latest()
        ->paginate(10);

        return view('frontend.orders', compact('orders'));
    }
}