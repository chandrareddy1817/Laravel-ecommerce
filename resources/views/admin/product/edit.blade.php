@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2>Edit Product</h2>

        <p class="text-muted mb-0">
            Update product information
        </p>
    </div>

    <a href="{{ route('admin.products.index') }}"
       class="btn btn-secondary">

        <i class="fa-solid fa-arrow-left me-1"></i>

        Back

    </a>

</div>


<div class="card shadow-sm">

    <div class="card-body">

        <form action="{{ route('admin.products.update', $product) }}"
              method="POST"  enctype="multipart/form-data">

            @csrf

            @method('PUT')


            <div class="row">

                {{-- Category --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Category
                    </label>

                    <select name="category_id"
                            class="form-select @error('category_id') is-invalid @enderror">

                        <option value="">
                            Select Category
                        </option>

                        @foreach($categories as $category)

                            <option value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>

                                {{ $category->name }}

                            </option>

                        @endforeach

                    </select>

                    @error('category_id')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Product Name --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Product Name
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name', $product->name) }}"
                           class="form-control @error('name') is-invalid @enderror">

                    @error('name')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- SKU --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        SKU
                    </label>

                    <input type="text"
                           name="sku"
                           value="{{ old('sku', $product->sku) }}"
                           class="form-control @error('sku') is-invalid @enderror">

                    @error('sku')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Price --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Price
                    </label>

                    <input type="number"
                           step="0.01"
                           name="price"
                           value="{{ old('price', $product->price) }}"
                           class="form-control @error('price') is-invalid @enderror">

                    @error('price')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Discount Price --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Discount Price
                    </label>

                    <input type="number"
                           step="0.01"
                           name="discount_price"
                           value="{{ old('discount_price', $product->discount_price) }}"
                           class="form-control @error('discount_price') is-invalid @enderror">

                    <small class="text-muted">
                        Leave empty if there is no discount.
                    </small>

                    @error('discount_price')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Stock --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Stock
                    </label>

                    <input type="number"
                           name="stock"
                           min="0"
                           value="{{ old('stock', $product->stock) }}"
                           class="form-control @error('stock') is-invalid @enderror">

                    @error('stock')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Description --}}

                <div class="col-12 mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea name="description"
                              rows="5"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>

                    @error('description')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Status --}}

                <div class="col-md-6 mb-4">

                    <label class="form-label">
                        Status
                    </label>

                    <select name="status"
                            class="form-select">

                        <option value="1"
                            {{ old('status', $product->status ? '1' : '0') == '1' ? 'selected' : '' }}>

                            Active

                        </option>

                        <option value="0"
                            {{ old('status', $product->status ? '1' : '0') == '0' ? 'selected' : '' }}>

                            Inactive

                        </option>

                    </select>

                </div>

                {{-- Current Image --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Current Image
                    </label>

                    @if($product->image)

                        <div class="mb-2">

                            <img src="{{ asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}"
                                width="120"
                                height="120"
                                class="rounded"
                                style="object-fit: cover;">

                        </div>

                    @else

                        <p class="text-muted">
                            No image uploaded.
                        </p>

                    @endif

                </div>


                {{-- New Image --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Change Image
                    </label>

                    <input type="file"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                        class="form-control @error('image') is-invalid @enderror">

                    <small class="text-muted">
                        Leave empty to keep the current image.
                    </small>

                    @error('image')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>


            <button type="submit"
                    class="btn btn-primary">

                <i class="fa-solid fa-save me-1"></i>

                Update Product

            </button>

        </form>

    </div>

</div>

@endsection