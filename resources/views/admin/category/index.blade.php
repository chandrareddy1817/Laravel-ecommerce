@extends('layouts.admin')

@section('title', 'Categories')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2>Categories</h2>
        <p class="text-muted mb-0">
            Manage your product categories
        </p>
    </div>

    <a href="{{ route('admin.categories.create') }}"
       class="btn btn-primary">

        <i class="fa-solid fa-plus me-1"></i>

        Add Category

    </a>

</div>


@if(session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif


@if($categories->count())

<div class="card shadow-sm">

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover mb-0">

                <thead class="table-dark">

                    <tr>

                        <th>#</th>

                        <th>Name</th>

                        <th>Slug</th>

                        <th>Status</th>

                        <th>Created</th>

                        <th class="text-end">Actions</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($categories as $category)

                    <tr>

                        <td>
                            {{ $categories->firstItem() + $loop->index }}
                        </td>

                        <td>
                            <strong>{{ $category->name }}</strong>
                        </td>

                        <td>
                            {{ $category->slug }}
                        </td>

                        <td>

                            @if($category->status)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Inactive
                                </span>

                            @endif

                        </td>

                        <td>
                            {{ $category->created_at->format('d M Y') }}
                        </td>

                        <td class="text-end">

                            <a href="{{ route('admin.categories.edit', $category) }}"
                               class="btn btn-sm btn-warning">

                                <i class="fa-solid fa-pen"></i>

                            </a>


                            <form action="{{ route('admin.categories.destroy', $category) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this category?');">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-sm btn-danger">

                                    <i class="fa-solid fa-trash"></i>

                                </button>

                            </form>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>


<div class="mt-4">

    {{ $categories->links() }}

</div>

@else

<div class="card shadow-sm">

    <div class="card-body text-center py-5">

        <i class="fa-solid fa-folder-open fa-3x text-muted mb-3"></i>

        <h4>No Categories Found</h4>

        <p class="text-muted">
            Start by creating your first category.
        </p>

        <a href="{{ route('admin.categories.create') }}"
           class="btn btn-primary">

            Add Category

        </a>

    </div>

</div>

@endif

@endsection