@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2>Add Product</h2>

    <a href="{{ route('admin.products.index') }}"
       class="btn btn-secondary">

        <i class="fa-solid fa-arrow-left me-1"></i>

        Back

    </a>

</div>


<div class="card shadow-sm">

    <div class="card-body">

        <form action="{{ route('admin.products.store') }}"
              method="POST" enctype="multipart/form-data">

            @csrf


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
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>

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
                           value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="Example: Classic T-Shirt">

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
                           value="{{ old('sku') }}"
                           class="form-control @error('sku') is-invalid @enderror"
                           placeholder="Example: TSHIRT-BLK-M-001">

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
                           value="{{ old('price') }}"
                           class="form-control @error('price') is-invalid @enderror"
                           placeholder="1499">

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
                           value="{{ old('discount_price') }}"
                           class="form-control @error('discount_price') is-invalid @enderror"
                           placeholder="999">

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
                           value="{{ old('stock', 0) }}"
                           min="0"
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
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Describe the product...">{{ old('description') }}</textarea>

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
                            {{ old('status', '1') == '1' ? 'selected' : '' }}>

                            Active

                        </option>

                        <option value="0"
                            {{ old('status') === '0' ? 'selected' : '' }}>

                            Inactive

                        </option>

                    </select>
                </div>

                    {{-- Product Image --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Product Image
                        </label>

                        <input type="file"
                            name="image"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="form-control @error('image') is-invalid @enderror">

                        <small class="text-muted">
                            JPG, JPEG, PNG or WEBP. Maximum 2MB.
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

                Save Product

            </button>

        </form>

    </div>

</div>

@endsection