@extends('layouts.frontend')

@section('title', 'Shopping Cart')

@section('content')

<h1 class="mb-4">
    Shopping Cart
</h1>

@if(session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif

@if(session('error'))

    <div class="alert alert-danger">
        {{ session('error') }}
    </div>

@endif


@if($cart->items->count())

    <div class="card shadow-sm">

        <div class="card-body">

            @php
                $total = 0;
            @endphp

            @foreach($cart->items as $item)

                @php
                    $price = $item->product->discount_price
                        ?? $item->product->price;

                    $subtotal = $price * $item->quantity;

                    $total += $subtotal;
                @endphp

                <div class="row align-items-center border-bottom py-3">

                    {{-- Image --}}

                    <div class="col-md-2">

                        @if($item->product->image)

                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                 alt="{{ $item->product->name }}"
                                 class="img-fluid rounded"
                                 style="height: 100px; width: 100px; object-fit: cover;">

                        @else

                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                 style="height: 100px; width: 100px;">

                                <i class="fa-solid fa-image text-muted"></i>

                            </div>

                        @endif

                    </div>


                    {{-- Product --}}

                    <div class="col-md-4">

                        <h5 class="mb-1">

                            {{ $item->product->name }}

                        </h5>

                        <small class="text-muted">

                            {{ $item->product->category->name }}

                        </small>

                    </div>


                    {{-- Price --}}

                    <div class="col-md-2">

                        ₹{{ number_format($price, 2) }}

                    </div>


                    {{-- Quantity --}}

                    <div class="col-md-2">

                        <form action="{{ route('cart.update', $item) }}"
                            method="POST">

                            @csrf
                            @method('PUT')

                            <div class="input-group">

                                <input type="number"
                                    name="quantity"
                                    value="{{ $item->quantity }}"
                                    min="1"
                                    max="{{ $item->product->stock }}"
                                    class="form-control text-center">

                                <button type="submit" class="btn btn-primary">Update</button>

                            </div>

                            <small class="text-muted">
                                Max: {{ $item->product->stock }}
                            </small>

                        </form>

                    </div>


                    {{-- Subtotal --}}

                    <div class="col-md-2">

                        <strong>

                            ₹{{ number_format($subtotal, 2) }}

                        </strong>

                        <form action="{{ route('cart.remove', $item) }}"
                            method="POST"
                            class="mt-2"
                            onsubmit="return confirm('Remove this product from your cart?');">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-sm btn-outline-danger">

                                <i class="fa-solid fa-trash me-1"></i>

                                Remove

                            </button>

                        </form>

                    </div>

                </div>

            @endforeach


            <div class="row mt-4">

                <div class="col-md-8"></div>

                <div class="col-md-4">

                    <div class="d-flex justify-content-between">

                        <strong>
                            Total
                        </strong>

                        <strong class="fs-4">

                            ₹{{ number_format($total, 2) }}

                        </strong>

                    </div>

                    <a href="{{ route('checkout.index') }}"
                        class="btn btn-success w-100 mt-3">

                            <i class="fa-solid fa-credit-card me-1"></i>

                            Proceed to Checkout

                    </a>

                </div>

            </div>

        </div>

    </div>

@else

    <div class="text-center py-5">

        <i class="fa-solid fa-cart-shopping fa-4x text-muted mb-3"></i>

        <h3>
            Your cart is empty
        </h3>

        <p class="text-muted">
            Add some products to your cart.
        </p>

        <a href="{{ route('shop') }}"
           class="btn btn-primary">

            Continue Shopping

        </a>

    </div>

@endif

@endsection