@extends('layouts.admin')

@section('title', 'Add Category')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2>Add Category</h2>

    <a href="{{ route('admin.categories.index') }}"
       class="btn btn-secondary">

        <i class="fa-solid fa-arrow-left me-1"></i>

        Back

    </a>

</div>


<div class="card shadow-sm">

    <div class="card-body">

        <form action="{{ route('admin.categories.store') }}"
              method="POST">

            @csrf


            <div class="mb-3">

                <label class="form-label">
                    Category Name
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       class="form-control @error('name') is-invalid @enderror"
                       placeholder="Example: Men's Clothing">

                @error('name')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <div class="mb-3">

                <label class="form-label">
                    Description
                </label>

                <textarea name="description"
                          rows="4"
                          class="form-control @error('description') is-invalid @enderror"
                          placeholder="Enter category description">{{ old('description') }}</textarea>

                @error('description')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <div class="mb-4">

                <label class="form-label">
                    Status
                </label>

                <select name="status"
                        class="form-select @error('status') is-invalid @enderror">

                    <option value="1"
                        {{ old('status', '1') == '1' ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0"
                        {{ old('status') === '0' ? 'selected' : '' }}>
                        Inactive
                    </option>

                </select>

                @error('status')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <button type="submit"
                    class="btn btn-primary">

                <i class="fa-solid fa-save me-1"></i>

                Save Category

            </button>

        </form>

    </div>

</div>

@endsection