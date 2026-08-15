@extends('layouts.frontend')

@section('title', 'Checkout')

@section('content')

<div class="mb-4">

    <h1>
        Checkout
    </h1>

    <p class="text-muted">
        Complete your shipping details and review your order.
    </p>

</div>


@if(session('error'))

    <div class="alert alert-danger">
        {{ session('error') }}
    </div>

@endif


@if($errors->any())

    <div class="alert alert-danger">

        <strong>Please fix the following:</strong>

        <ul class="mb-0 mt-2">

            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


<div class="row g-4">


    {{-- SHIPPING DETAILS --}}

    <div class="col-lg-7">

        <div class="card shadow-sm">

            <div class="card-header">

                <h5 class="mb-0">
                    <i class="fa-solid fa-location-dot me-2"></i>
                    Shipping Information
                </h5>

            </div>


            <div class="card-body">

                <form action="{{ route('checkout.place') }}"
                      method="POST">

                    @csrf


                    {{-- Address --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Address
                        </label>

                        <textarea
                            name="shipping_address"
                            rows="4"
                            class="form-control @error('shipping_address') is-invalid @enderror"
                            placeholder="House number, street, area">{{ old('shipping_address') }}</textarea>

                        @error('shipping_address')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="row">


                        {{-- City --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                City
                            </label>

                            <input
                                type="text"
                                name="shipping_city"
                                value="{{ old('shipping_city') }}"
                                class="form-control @error('shipping_city') is-invalid @enderror">

                            @error('shipping_city')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- State --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                State
                            </label>

                            <input
                                type="text"
                                name="shipping_state"
                                value="{{ old('shipping_state') }}"
                                class="form-control @error('shipping_state') is-invalid @enderror">

                            @error('shipping_state')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Pincode --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Pincode
                            </label>

                            <input
                                type="text"
                                name="shipping_pincode"
                                value="{{ old('shipping_pincode') }}"
                                class="form-control @error('shipping_pincode') is-invalid @enderror"
                                maxlength="10">

                            @error('shipping_pincode')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Phone --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Phone
                            </label>

                            <input
                                type="text"
                                name="shipping_phone"
                                value="{{ old('shipping_phone') }}"
                                class="form-control @error('shipping_phone') is-invalid @enderror"
                                maxlength="15">

                            @error('shipping_phone')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    <div class="d-flex justify-content-between mt-3">

                        <a href="{{ route('cart.index') }}"
                           class="btn btn-outline-secondary">

                            <i class="fa-solid fa-arrow-left me-1"></i>

                            Back to Cart

                        </a>


                        <button type="submit"
                                class="btn btn-primary">

                            Continue to Payment

                            <i class="fa-solid fa-arrow-right ms-1"></i>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>



    {{-- ORDER SUMMARY --}}

    <div class="col-lg-5">

        <div class="card shadow-sm">

            <div class="card-header">

                <h5 class="mb-0">
                    Order Summary
                </h5>

            </div>


            <div class="card-body">


                @foreach($cart->items as $item)

                    @php

                        $price = $item->product->discount_price
                            ?? $item->product->price;

                        $itemTotal = $price * $item->quantity;

                    @endphp


                    <div class="d-flex align-items-center mb-3">


                        @if($item->product->image)

                            <img
                                src="{{ asset('storage/' . $item->product->image) }}"
                                alt="{{ $item->product->name }}"
                                width="60"
                                height="60"
                                class="rounded me-3"
                                style="object-fit: cover;">

                        @else

                            <div
                                class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                style="width:60px;height:60px;">

                                <i class="fa-solid fa-image text-muted"></i>

                            </div>

                        @endif


                        <div class="flex-grow-1">

                            <div class="fw-semibold">

                                {{ $item->product->name }}

                            </div>

                            <small class="text-muted">

                                Qty: {{ $item->quantity }}

                            </small>

                        </div>


                        <div>

                            ₹{{ number_format($itemTotal, 2) }}

                        </div>

                    </div>

                @endforeach


                <hr>


                <div class="d-flex justify-content-between mb-2">

                    <span>
                        Subtotal
                    </span>

                    <span>
                        ₹{{ number_format($subtotal, 2) }}
                    </span>

                </div>


                <div class="d-flex justify-content-between mb-2">

                    <span>
                        Shipping
                    </span>

                    <span class="text-success">

                        @if($shipping > 0)

                            ₹{{ number_format($shipping, 2) }}

                        @else

                            FREE

                        @endif

                    </span>

                </div>


                <hr>


                <div class="d-flex justify-content-between">

                    <strong class="fs-5">
                        Total
                    </strong>

                    <strong class="fs-5">
                        ₹{{ number_format($total, 2) }}
                    </strong>

                </div>


            </div>

        </div>


        <div class="alert alert-info mt-3">

            <i class="fa-solid fa-shield-halved me-2"></i>

            Your payment will be processed securely.

        </div>

    </div>

</div>

@endsection