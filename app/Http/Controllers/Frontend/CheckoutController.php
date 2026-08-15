<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Payment;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())
            ->with('items.product.category')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $subtotal = 0;

        foreach ($cart->items as $item) {

            $price = $item->product->discount_price
                ?? $item->product->price;

            $subtotal += $price * $item->quantity;
        }

        $shipping = 0;

        $total = $subtotal + $shipping;

        return view('frontend.checkout', compact(
            'cart',
            'subtotal',
            'shipping',
            'total'
        ));
    }


    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'required|string|max:100',
            'shipping_pincode' => 'required|string|max:10',
            'shipping_phone' => 'required|string|max:15',
        ]);

        $cart = Cart::where('user_id', auth()->id())
            ->with('items.product')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Stock Again
        |--------------------------------------------------------------------------
        */

        foreach ($cart->items as $item) {

            $product = $item->product;

            if (!$product->status) {
                return back()->with(
                    'error',
                    $product->name . ' is no longer available.'
                );
            }

            if ($item->quantity > $product->stock) {
                return back()->with(
                    'error',
                    'Only ' . $product->stock .
                    ' units of ' . $product->name .
                    ' are available.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Total
        |--------------------------------------------------------------------------
        */

        $subtotal = 0;

        foreach ($cart->items as $item) {

            $price = $item->product->discount_price
                ?? $item->product->price;

            $subtotal += $price * $item->quantity;
        }

        $shipping = 0;

        $total = $subtotal + $shipping;


        /*
        |--------------------------------------------------------------------------
        | Create Order
        |--------------------------------------------------------------------------
        */

        $order = DB::transaction(function () use (
            $validated,
            $cart,
            $subtotal,
            $shipping,
            $total
        ) {

            $order = Order::create([
                'user_id' => auth()->id(),

                'order_number' => 'ORD-' .
                    strtoupper(Str::random(10)),

                'subtotal' => $subtotal,

                'shipping_amount' => $shipping,

                'total_amount' => $total,

                'status' => 'pending',

                'payment_status' => 'pending',

                'payment_method' => null,

                'shipping_address' =>
                    $validated['shipping_address'],

                'shipping_city' =>
                    $validated['shipping_city'],

                'shipping_state' =>
                    $validated['shipping_state'],

                'shipping_pincode' =>
                    $validated['shipping_pincode'],

                'shipping_phone' =>
                    $validated['shipping_phone'],
            ]);


            /*
            |--------------------------------------------------------------------------
            | Create Order Items
            |--------------------------------------------------------------------------
            */

            foreach ($cart->items as $item) {

                $product = $item->product;

                $price = $product->discount_price
                    ?? $product->price;

                OrderItem::create([
                    'order_id' => $order->id,

                    'product_id' => $product->id,

                    'product_name' => $product->name,

                    'sku' => $product->sku,

                    'price' => $price,

                    'quantity' => $item->quantity,

                    'subtotal' =>
                        $price * $item->quantity,
                ]);
            }

            return $order;
        });


        return redirect()
            ->route('checkout.payment', $order)
            ->with('success', 'Order created successfully.');
    }
    public function payment(Order $order)
    {
        // Make sure this order belongs to the logged-in user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->payment_status === 'paid') {
            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'This order has already been paid.');
        }

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        /*
        |--------------------------------------------------------------------------
        | Amount must be in paise
        |--------------------------------------------------------------------------
        */

        $amount = (int) round(
            $order->total_amount * 100
        );

        /*
        |--------------------------------------------------------------------------
        | Create Razorpay Order
        |--------------------------------------------------------------------------
        */

        $razorpayOrder = $api->order->create([
            'receipt' => $order->order_number,
            'amount' => $amount,
            'currency' => 'INR',
            'notes' => [
                'order_number' => $order->order_number,
                'user_id' => (string) auth()->id(),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create Payment Record
        |--------------------------------------------------------------------------
        */

        Payment::updateOrCreate(
            [
                'order_id' => $order->id,
            ],
            [
                'provider' => 'razorpay',
                'gateway_order_id' => $razorpayOrder['id'],
                'amount' => $order->total_amount,
                'currency' => 'INR',
                'status' => 'created',
            ]
        );

        return view('frontend.payment', [
            'order' => $order,
            'razorpayOrder' => $razorpayOrder,
            'razorpayKey' => config('services.razorpay.key'),
        ]);
    }
    public function verifyPayment(Request $request, Order $order)
    {
        // Make sure the order belongs to the logged-in user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $payment = $order->payment;

        if (!$payment) {
            return redirect()
                ->route('checkout.index')
                ->with('error', 'Payment record was not found.');
        }

        // Make sure the Razorpay order belongs to our order
        if ($payment->gateway_order_id !== $validated['razorpay_order_id']) {
            return redirect()
                ->route('checkout.index')
                ->with('error', 'Invalid payment order.');
        }

        /*
        |--------------------------------------------------------------------------
        | Verify Razorpay Signature
        |--------------------------------------------------------------------------
        */

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        try {

            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $validated['razorpay_order_id'],
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature' => $validated['razorpay_signature'],
            ]);

        } catch (\Throwable $e) {

            Log::warning('Razorpay signature verification failed.', [
                'order_id' => $order->id,
                'razorpay_order_id' => $validated['razorpay_order_id'],
            ]);

            return redirect()
                ->route('checkout.index')
                ->with(
                    'error',
                    'Payment verification failed. Your order was not confirmed.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Fetch Actual Payment From Razorpay
        |--------------------------------------------------------------------------
        */

        try {

            $razorpayPayment =
                $api->payment
                    ->fetch($validated['razorpay_payment_id']);

        } catch (\Throwable $e) {

            Log::error('Unable to fetch Razorpay payment.', [
                'order_id' => $order->id,
                'payment_id' => $validated['razorpay_payment_id'],
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('checkout.index')
                ->with(
                    'error',
                    'Unable to verify payment status. Please try again.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Verify Amount
        |--------------------------------------------------------------------------
        */

        $expectedAmount = (int) round(
            $order->total_amount * 100
        );

        if ((int) $razorpayPayment['amount'] !== $expectedAmount) {

            Log::warning('Razorpay amount mismatch.', [
                'order_id' => $order->id,
                'expected' => $expectedAmount,
                'received' => $razorpayPayment['amount'],
            ]);

            return redirect()
                ->route('checkout.index')
                ->with(
                    'error',
                    'Payment amount does not match the order.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Only Fulfil Captured Payments
        |--------------------------------------------------------------------------
        */

        if ($razorpayPayment['status'] !== 'captured') {

            $payment->update([
                'gateway_payment_id' =>
                    $validated['razorpay_payment_id'],

                'gateway_signature' =>
                    $validated['razorpay_signature'],

                'status' =>
                    $razorpayPayment['status'],
            ]);

            return redirect()
                ->route('orders.show', $order)
                ->with(
                    'error',
                    'Payment has not been captured yet.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Complete Order
        |--------------------------------------------------------------------------
        */

        $this->completePaidOrderFromWebhook(
            $order,
            $validated['razorpay_payment_id'],
            $validated['razorpay_signature']
        );

        return redirect()
            ->route('orders.success', $order);
    }
    public function completePaidOrderFromWebhook(
        Order $order,
        string $paymentId,
        ?string $signature = null
    ): void {
        DB::transaction(function () use (
            $order,
            $paymentId,
            $signature
        ) {

            /*
            |--------------------------------------------------------------------------
            | Lock Order
            |--------------------------------------------------------------------------
            */

            $order = Order::where('id', $order->id)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Already Completed?
            |--------------------------------------------------------------------------
            */

            if ($order->payment_status === 'paid') {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Load Payment
            |--------------------------------------------------------------------------
            */

            $payment = Payment::where('order_id', $order->id)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Load Order Items
            |--------------------------------------------------------------------------
            */

            $order->load('items');


            /*
            |--------------------------------------------------------------------------
            | Lock Products + Check Stock
            |--------------------------------------------------------------------------
            */

            foreach ($order->items as $item) {

                $product = \App\Models\Product::where(
                    'id',
                    $item->product_id
                )
                ->lockForUpdate()
                ->first();

                if (!$product) {

                    throw new \Exception(
                        "Product {$item->product_name} no longer exists."
                    );
                }

                if ($product->stock < $item->quantity) {

                    throw new \Exception(
                        "Insufficient stock for {$product->name}."
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Reduce Stock
            |--------------------------------------------------------------------------
            */

            foreach ($order->items as $item) {

                $product = \App\Models\Product::where(
                    'id',
                    $item->product_id
                )
                ->lockForUpdate()
                ->first();

                $product->decrement(
                    'stock',
                    $item->quantity
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Update Payment
            |--------------------------------------------------------------------------
            */

            $payment->update([
                'gateway_payment_id' => $paymentId,
                'gateway_signature' => $signature,
                'status' => 'paid',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Update Order
            |--------------------------------------------------------------------------
            */

            $order->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
                'payment_method' => 'razorpay',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Clear User Cart
            |--------------------------------------------------------------------------
            */

            $cart = Cart::where(
                'user_id',
                $order->user_id
            )->first();

            if ($cart) {
                $cart->items()->delete();
            }
        });
    }
}