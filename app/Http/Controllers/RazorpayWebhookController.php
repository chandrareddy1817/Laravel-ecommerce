<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;

class RazorpayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $signature = $request->header(
            'X-Razorpay-Signature'
        );

        $webhookSecret =
            config('services.razorpay.webhook_secret');

        if (!$signature || !$webhookSecret) {

            return response()->json([
                'message' => 'Invalid webhook configuration.'
            ], 400);
        }

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT:
        | Use the RAW request body
        |--------------------------------------------------------------------------
        */

        $rawBody = $request->getContent();

        $expectedSignature = hash_hmac(
            'sha256',
            $rawBody,
            $webhookSecret
        );

        if (!hash_equals(
            $expectedSignature,
            $signature
        )) {

            Log::warning(
                'Invalid Razorpay webhook signature.'
            );

            return response()->json([
                'message' => 'Invalid signature.'
            ], 400);
        }


        $payload = json_decode(
            $rawBody,
            true
        );

        $event = $payload['event'] ?? null;


        /*
        |--------------------------------------------------------------------------
        | Payment Captured
        |--------------------------------------------------------------------------
        */

        if ($event === 'payment.captured') {

            $paymentEntity =
                $payload['payload']['payment']['entity']
                ?? null;

            if (!$paymentEntity) {
                return response()->json([
                    'message' => 'Invalid payload.'
                ], 400);
            }

            $razorpayOrderId =
                $paymentEntity['order_id']
                ?? null;

            $razorpayPaymentId =
                $paymentEntity['id']
                ?? null;


            if (!$razorpayOrderId || !$razorpayPaymentId) {

                return response()->json([
                    'message' => 'Missing payment information.'
                ], 400);
            }


            $payment = Payment::where(
                'gateway_order_id',
                $razorpayOrderId
            )->first();


            if (!$payment) {

                Log::warning(
                    'Razorpay webhook payment record not found.',
                    [
                        'gateway_order_id' =>
                            $razorpayOrderId
                    ]
                );

                return response()->json([
                    'message' => 'Payment not found.'
                ], 200);
            }


            $payment->update([
                'gateway_payment_id' =>
                    $razorpayPaymentId,

                'status' => 'paid',
            ]);


            $order = Order::find(
                $payment->order_id
            );


            if ($order) {

                /*
                |--------------------------------------------------------------------------
                | Important:
                | We will use the same fulfillment logic.
                |--------------------------------------------------------------------------
                */

                app(
                    \App\Http\Controllers\Frontend\CheckoutController::class
                )->completePaidOrderFromWebhook(
                    $order,
                    $razorpayPaymentId
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Payment Failed
        |--------------------------------------------------------------------------
        */

        if ($event === 'payment.failed') {

            $paymentEntity =
                $payload['payload']['payment']['entity']
                ?? null;

            if ($paymentEntity) {

                $razorpayOrderId =
                    $paymentEntity['order_id']
                    ?? null;

                if ($razorpayOrderId) {

                    Payment::where(
                        'gateway_order_id',
                        $razorpayOrderId
                    )->update([
                        'gateway_payment_id' =>
                            $paymentEntity['id'] ?? null,

                        'status' => 'failed',
                    ]);
                }
            }
        }


        return response()->json([
            'success' => true
        ]);
    }
}