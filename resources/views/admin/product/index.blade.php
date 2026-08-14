@extends('layouts.admin')

@section('title', 'Products')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2>Products</h2>

        <p class="text-muted mb-0">
            Manage your store products
        </p>
    </div>

    <a href="{{ route('admin.products.create') }}"
       class="btn btn-primary">

        <i class="fa-solid fa-plus me-1"></i>

        Add Product

    </a>

</div>


@if(session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif


<div class="card shadow-sm">

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover mb-0">

                <thead class="table-dark">

                    <tr>

                        <th>#</th>
                        <th>Image</th>
                        <th>Product</th>

                        <th>Category</th>

                        <th>SKU</th>

                        <th>Price</th>

                        <th>Stock</th>

                        <th>Status</th>

                        <th class="text-end">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($products as $product)

                    <tr>

                        <td>
                            {{ $products->firstItem() + $loop->index }}
                        </td>

                        <td>

                            @if($product->image)

                                <img src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->name }}"
                                    width="60"
                                    height="60"
                                    class="rounded"
                                    style="object-fit: cover;">

                            @else

                                <span class="text-muted">
                                    No Image
                                </span>

                            @endif

                        </td>

                        <td>

                            <strong>
                                {{ $product->name }}
                            </strong>

                        </td>

                        <td>
                            {{ $product->category->name }}
                        </td>

                        <td>
                            {{ $product->sku }}
                        </td>

                        <td>

                            ₹{{ number_format($product->price, 2) }}

                            @if($product->discount_price)

                                <br>

                                <small class="text-success">

                                    ₹{{ number_format($product->discount_price, 2) }}

                                </small>

                            @endif

                        </td>

                        <td>

                            @if($product->stock > 0)

                                <span class="badge bg-success">

                                    {{ $product->stock }}

                                </span>

                            @else

                                <span class="badge bg-danger">

                                    Out of Stock

                                </span>

                            @endif

                        </td>

                        <td>

                            @if($product->status)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Inactive
                                </span>

                            @endif

                        </td>

                        <td class="text-end">

                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="btn btn-sm btn-warning">

                                <i class="fa-solid fa-pen"></i>

                            </a>

                            <form action="{{ route('admin.products.destroy', $product) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Are you sure you want to delete this product?');">

                                @csrf

                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-sm btn-danger">

                                    <i class="fa-solid fa-trash"></i>

                                </button>

                            </form>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="9"
                            class="text-center py-5">

                            <h5>No Products Found</h5>

                            <p class="text-muted">
                                Create your first product.
                            </p>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


<div class="mt-4">

    {{ $products->links() }}

</div>

@endsection