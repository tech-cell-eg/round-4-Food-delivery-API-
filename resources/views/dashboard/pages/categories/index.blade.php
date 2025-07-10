@extends('dashboard.layouts.master')

@section("title", "Categories")

@section("content")


    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">

            <!-- breadcrumb -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Categories</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Categories</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end breadcrumb -->

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="row row-cols-1 row-cols-md-3 row-cols-xl-3">
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Breakfast Categories</p>
                                    <h5 class="my-1 text-info">{{ $stats['breakfast'] }}</h5>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                                    <i class='bx bxs-sun'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Lunch Categories</p>
                                    <h5 class="my-1 text-warning">{{ $stats['lunch'] }}</h5>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto">
                                    <i class='bx bxs-dish'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Dinner Categories</p>
                                    <h5 class="my-1 text-danger">{{ $stats['dinner'] }}</h5>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                                    <i class='bx bxs-moon'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                        <div class="ms-auto"><a href="{{ route('admin.categories.create') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Add New Category</a></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>Category ID</th>
                                <th>Category Image</th>
                                <th>Category Name</th>
                                <th>Meal Type</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>
                                    @if($category->image && \Storage::disk('public')->exists($category->image))
                                        <img src="{{ asset('storage/' . $category->image) }}"
                                             alt="{{ $category->name }}"
                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;"
                                             loading="lazy"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="d-none align-items-center justify-content-center"
                                             style="width: 60px; height: 60px; background-color: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6;">
                                            <i class="bx bx-image" style="font-size: 24px; color: #6c757d;"></i>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center justify-content-center"
                                             style="width: 60px; height: 60px; background-color: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6;">
                                            <i class="bx bx-image" style="font-size: 24px; color: #6c757d;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <span class="badge 
                                        @if($category->meal_type == 'breakfast') bg-info 
                                        @elseif($category->meal_type == 'lunch') bg-warning 
                                        @else bg-danger @endif">
                                        {{ ucfirst($category->meal_type) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('admin.categories.edit', $category->id) }}"
                                           class="btn btn-sm btn-primary"
                                           title="Edit">
                                            <i class='bx bxs-edit'></i>
                                        </a>

                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="ms-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this category?')"
                                                    title="Delete">
                                                <i class='bx bxs-trash'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bx bx-category-alt" style="font-size: 48px; color: #dee2e6; margin-bottom: 16px;"></i>
                                        <h6 class="text-muted mb-2">No categories found</h6>
                                        <p class="text-muted small mb-3">Start by creating your first category</p>
                                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                                            <i class="bx bx-plus me-1"></i>Create Category
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $categories->links("pagination::bootstrap-4") }}
                    </div>
                </div>
            </div>


        </div>
    </div>
    <!--end page wrapper -->

@endsection
