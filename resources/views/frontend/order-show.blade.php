@extends('layouts.frontend')

@section('title', 'Order Details')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h1>
            Order #{{ $order->order_number }}
        </h1>

        <p class="text-muted mb-0">

            {{ $order->created_at->format('d M Y, h:i A') }}

        </p>

    </div>


    <a href="{{ route('orders.index') }}"
       class="btn btn-outline-secondary">

        My Orders

    </a>

</div>


<div class="row g-4">

    <div class="col-lg-8">

        <div class="card shadow-sm">

            <div class="card-header">

                <h5 class="mb-0">
                    Products
                </h5>

            </div>

            <div class="card-body">

                @foreach($order->items as $item)

                    <div class="d-flex align-items-center border-bottom py-3">

                        @if($item->product && $item->product->image)

                            <img
                                src="{{ asset(
                                    'storage/' .
                                    $item->product->image
                                ) }}"
                                width="70"
                                height="70"
                                class="rounded me-3"
                                style="object-fit: cover;">

                        @else

                            <div
                                class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                style="width:70px;height:70px;">

                                <i class="fa-solid fa-image text-muted"></i>

                            </div>

                        @endif


                        <div class="flex-grow-1">

                            <h6 class="mb-1">

                                {{ $item->product_name }}

                            </h6>

                            <small class="text-muted">

                                SKU: {{ $item->sku }}

                            </small>

                            <br>

                            <small class="text-muted">

                                ₹{{ number_format(
                                    $item->price,
                                    2
                                ) }}

                                × {{ $item->quantity }}

                            </small>

                        </div>


                        <strong>

                            ₹{{ number_format(
                                $item->subtotal,
                                2
                            ) }}

                        </strong>

                    </div>

                @endforeach

            </div>

        </div>

    </div>


    <div class="col-lg-4">

        <div class="card shadow-sm mb-3">

            <div class="card-header">

                <h5 class="mb-0">
                    Order Status
                </h5>

            </div>

            <div class="card-body">

                <p class="mb-2">

                    Payment:

                    @if($order->payment_status === 'paid')

                        <span class="badge bg-success">
                            Paid
                        </span>

                    @else

                        <span class="badge bg-warning text-dark">
                            {{ ucfirst($order->payment_status) }}
                        </span>

                    @endif

                </p>


                <p class="mb-0">

                    Order:

                    <span class="badge bg-primary">

                        {{ ucfirst($order->status) }}

                    </span>

                </p>

            </div>

        </div>


        <div class="card shadow-sm">

            <div class="card-header">

                <h5 class="mb-0">
                    Delivery Address
                </h5>

            </div>

            <div class="card-body">

                {{ $order->shipping_address }}

                <br>

                {{ $order->shipping_city }},
                {{ $order->shipping_state }}

                <br>

                {{ $order->shipping_pincode }}

                <br>

                Phone:
                {{ $order->shipping_phone }}

            </div>

        </div>

    </div>

</div>

@endsection