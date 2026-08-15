@extends('layouts.frontend')

@section('title', 'Order Successful')

@section('content')

<div class="row justify-content-center">

    <div class="col-lg-8">

        <div class="card shadow-sm">

            <div class="card-body text-center p-5">

                <div class="mb-4">

                    <i class="fa-solid fa-circle-check text-success"
                       style="font-size: 70px;"></i>

                </div>

                <h1 class="text-success">
                    Payment Successful!
                </h1>

                <p class="lead mt-3">
                    Thank you for your order.
                </p>

                <p class="text-muted">

                    Your order
                    <strong>
                        #{{ $order->order_number }}
                    </strong>
                    has been confirmed.

                </p>


                <div class="alert alert-success mt-4">

                    Payment Status:
                    <strong>
                        Paid
                    </strong>

                </div>


                <div class="card mt-4">

                    <div class="card-body text-start">

                        <h5 class="mb-3">
                            Order Summary
                        </h5>


                        @foreach($order->items as $item)

                            <div class="d-flex justify-content-between mb-2">

                                <span>

                                    {{ $item->product_name }}

                                    × {{ $item->quantity }}

                                </span>

                                <span>

                                    ₹{{ number_format(
                                        $item->subtotal,
                                        2
                                    ) }}

                                </span>

                            </div>

                        @endforeach


                        <hr>


                        <div class="d-flex justify-content-between">

                            <strong>
                                Total
                            </strong>

                            <strong>
                                ₹{{ number_format(
                                    $order->total_amount,
                                    2
                                ) }}
                            </strong>

                        </div>

                    </div>

                </div>


                <div class="mt-4">

                    <a href="{{ route('orders.show', $order) }}"
                       class="btn btn-primary me-2">

                        View Order

                    </a>

                    <a href="{{ route('shop') }}"
                       class="btn btn-outline-secondary">

                        Continue Shopping

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection