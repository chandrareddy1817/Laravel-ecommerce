@extends('layouts.frontend')

@section('title', $product->name)

@section('content')

<div class="row g-5">

    {{-- Product Image --}}

    <div class="col-md-6">

        @if($product->image)

            <img src="{{ asset('storage/' . $product->image) }}"
                 alt="{{ $product->name }}"
                 class="img-fluid rounded shadow-sm"
                 style="width: 100%; max-height: 500px; object-fit: cover;">

        @else

            <div class="d-flex align-items-center justify-content-center bg-light rounded"
                 style="height: 500px;">

                <i class="fa-solid fa-image fa-5x text-muted"></i>

            </div>

        @endif

    </div>


    {{-- Product Information --}}

    <div class="col-md-6">

        <small class="text-muted">
            {{ $product->category->name }}
        </small>

        <h1 class="mt-2">
            {{ $product->name }}
        </h1>


        <div class="my-4">

            @if($product->discount_price)

                <span class="fs-3 fw-bold text-success">

                    ₹{{ number_format($product->discount_price, 2) }}

                </span>

                <del class="fs-5 text-muted ms-2">

                    ₹{{ number_format($product->price, 2) }}

                </del>

            @else

                <span class="fs-3 fw-bold">

                    ₹{{ number_format($product->price, 2) }}

                </span>

            @endif

        </div>


        <p class="text-muted">

            {{ $product->description }}

        </p>


        <div class="mb-4">

            @if($product->stock > 0)

                <span class="badge bg-success fs-6">

                    {{ $product->stock }} in stock

                </span>

            @else

                <span class="badge bg-danger fs-6">

                    Out of Stock

                </span>

            @endif

        </div>


        @if($product->stock > 0)

            <form id="addToCartForm"
                action="{{ route('cart.add', $product) }}"
                method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Quantity
                    </label>

                    <input type="number"
                        name="quantity"
                        value="1"
                        min="1"
                        max="{{ $product->stock }}"
                        class="form-control"
                        style="width: 120px;">

                </div>

                <button type="submit"
                        id="addToCartButton"
                        class="btn btn-primary btn-lg">

                    <i class="fa-solid fa-cart-shopping me-2"></i>

                    Add to Cart

                </button>

            </form>
            <div id="cartMessage"
                class="mt-3">
            </div>

        @else

            <button class="btn btn-secondary btn-lg"
                    disabled>

                Out of Stock

            </button>

        @endif

    </div>

</div>

@endsection

@section('scripts')

<script>

document
    .getElementById('addToCartForm')
    .addEventListener('submit', async function(event) {

        event.preventDefault();

        const form = this;

        const button =
            document.getElementById('addToCartButton');

        const message =
            document.getElementById('cartMessage');

        button.disabled = true;

        button.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>Adding...';

        try {

            const response = await fetch(form.action, {

                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN':
                        document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'),

                    'Accept': 'application/json'
                },

                body: new FormData(form)

            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Something went wrong.');
            }

            message.innerHTML = `
                <div class="alert alert-success">
                    ${data.message}
                </div>
            `;

            updateCartCount(data.cart_count);

            button.innerHTML =
                '<i class="fa-solid fa-check me-2"></i>Added to Cart';

            setTimeout(() => {

                button.disabled = false;

                button.innerHTML =
                    '<i class="fa-solid fa-cart-shopping me-2"></i>Add to Cart';

            }, 2000);

        } catch (error) {

            message.innerHTML = `
                <div class="alert alert-danger">
                    ${error.message}
                </div>
            `;

            button.disabled = false;

            button.innerHTML =
                '<i class="fa-solid fa-cart-shopping me-2"></i>Add to Cart';
        }

    });


function updateCartCount(count)
{
    const badge =
        document.getElementById('cartCount');

    if (badge) {
        badge.textContent = count;
    }
}

</script>

@endsection