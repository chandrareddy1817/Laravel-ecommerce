@extends('layouts.frontend')

@section('title', 'Payment')

@section('content')

<div class="row justify-content-center">

    <div class="col-md-7 col-lg-6">

        <div class="card shadow-sm">

            <div class="card-body text-center p-5">

                <h2 class="mb-3">
                    Complete Payment
                </h2>

                <p class="text-muted">
                    Order #{{ $order->order_number }}
                </p>

                <div class="my-4">

                    <div class="text-muted">
                        Amount to Pay
                    </div>

                    <div class="display-5 fw-bold">

                        ₹{{ number_format($order->total_amount, 2) }}

                    </div>

                </div>


                <button
                    id="payButton"
                    class="btn btn-primary btn-lg w-100">

                    <i class="fa-solid fa-lock me-2"></i>

                    Pay ₹{{ number_format($order->total_amount, 2) }}

                </button>


                <div class="mt-4 text-muted small">

                    <i class="fa-solid fa-shield-halved me-1"></i>

                    Secure payment powered by Razorpay

                </div>

            </div>

        </div>

    </div>

</div>


<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>

document
    .getElementById('payButton')
    .addEventListener('click', function () {

        const options = {

            key: @json($razorpayKey),

            amount: @json($razorpayOrder['amount']),

            currency: @json($razorpayOrder['currency']),

            name: 'Laravel Ecommerce',

            description:
                'Order #{{ $order->order_number }}',

            order_id:
                @json($razorpayOrder['id']),

            prefill: {
                name: @json(auth()->user()->name),
                email: @json(auth()->user()->email),
            },

            theme: {
                color: '#0d6efd'
            },

            handler: function (response) {

                /*
                |--------------------------------------------------------------------------
                | Send successful payment details to Laravel
                |--------------------------------------------------------------------------
                */

                const form =
                    document.createElement('form');

                form.method = 'POST';

                form.action =
                    "{{ route('payment.verify', $order) }}";


                const csrf =
                    document.createElement('input');

                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value =
                    "{{ csrf_token() }}";

                form.appendChild(csrf);


                const paymentId =
                    document.createElement('input');

                paymentId.type = 'hidden';
                paymentId.name =
                    'razorpay_payment_id';

                paymentId.value =
                    response.razorpay_payment_id;

                form.appendChild(paymentId);


                const razorpayOrderId =
                    document.createElement('input');

                razorpayOrderId.type = 'hidden';
                razorpayOrderId.name =
                    'razorpay_order_id';

                razorpayOrderId.value =
                    response.razorpay_order_id;

                form.appendChild(razorpayOrderId);


                const signature =
                    document.createElement('input');

                signature.type = 'hidden';
                signature.name =
                    'razorpay_signature';

                signature.value =
                    response.razorpay_signature;

                form.appendChild(signature);


                document.body.appendChild(form);

                form.submit();
            }

        };


        const razorpay =
            new Razorpay(options);

        razorpay.open();

    });

</script>

@endsection