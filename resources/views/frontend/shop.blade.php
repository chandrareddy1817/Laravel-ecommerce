@extends('layouts.frontend')

@section('title', 'Shop')

@section('content')

<div class="mb-4">

    <h1>Shop</h1>

    <p class="text-muted">
        Explore our latest products
    </p>

</div>


<div class="row">

    {{-- Categories Sidebar --}}

    <div class="col-lg-3 mb-4">

        <div class="card shadow-sm">

            <div class="card-header bg-dark text-white">

                <i class="fa-solid fa-list me-2"></i>

                Categories

            </div>

            <div class="list-group list-group-flush">

                <a href="{{ route('shop') }}"
                   class="list-group-item list-group-item-action">

                    All Products

                </a>

               @foreach($categories as $category)

                    <a href="{{ route('shop', ['category' => $category->slug]) }}"
                    class="list-group-item list-group-item-action
                    {{ request('category') == $category->slug ? 'active' : '' }}">

                        {{ $category->name }}

                    </a>

                @endforeach

            </div>

        </div>

    </div>


    {{-- Products --}}

    <div class="col-lg-9">
        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <form action="{{ route('shop') }}" method="GET">

                    @if(request('category'))

                        <input type="hidden"
                            name="category"
                            value="{{ request('category') }}">

                    @endif

                    <div class="row g-2">

                        <div class="col-md-8">

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control"
                                placeholder="Search products..."
                            >

                        </div>

                        <div class="col-md-2">

                            <button type="submit"
                                    class="btn btn-primary w-100">

                                <i class="fa-solid fa-search me-1"></i>

                                Search

                            </button>

                        </div>

                        <div class="col-md-2">

                            <a href="{{ route('shop') }}"
                            class="btn btn-outline-secondary w-100">

                                Clear

                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>
        <div class="row g-4">

            @forelse($products as $product)

                <div class="col-md-6 col-xl-4">

                    <div class="card h-100 shadow-sm">

                        @if($product->image)

                            <img src="{{ asset('storage/' . $product->image) }}"
                                 class="card-img-top"
                                 alt="{{ $product->name }}"
                                 style="height: 250px; object-fit: cover;">

                        @else

                            <div class="d-flex align-items-center justify-content-center bg-light"
                                 style="height: 250px;">

                                <i class="fa-solid fa-image fa-3x text-muted"></i>

                            </div>

                        @endif

                        @if($product->discount_price)

                            @php
                                $discountPercentage =
                                    (($product->price - $product->discount_price)
                                    / $product->price) * 100;
                            @endphp

                            <span class="badge bg-danger position-absolute m-2">

                                {{ round($discountPercentage) }}% OFF

                            </span>

                        @endif


                        <div class="card-body d-flex flex-column">

                            <small class="text-muted">
                                {{ $product->category->name }}
                            </small>

                            <h5 class="card-title mt-1">
                                {{ $product->name }}
                            </h5>


                            <div class="mb-3">

                                @if($product->discount_price)

                                    <span class="fw-bold text-success">

                                        ₹{{ number_format($product->discount_price, 2) }}

                                    </span>

                                    <del class="text-muted ms-2">

                                        ₹{{ number_format($product->price, 2) }}

                                    </del>

                                @else

                                    <span class="fw-bold">

                                        ₹{{ number_format($product->price, 2) }}

                                    </span>

                                @endif

                            </div>


                            <a href="{{ route('product.show', $product) }}"
                               class="btn btn-primary mt-auto">

                                View Product

                            </a>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">

                    <div class="alert alert-info">

                        No products available at the moment.

                    </div>

                </div>

            @endforelse

        </div>


        {{-- Pagination --}}

        <div class="mt-5">

            {{ $products->links() }}

        </div>

    </div>

</div>

@endsection