@extends('dashboard.layouts.master')

@section("title", "Ingredients")

@section("content")

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">

            <!-- breadcrumb -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Ingredients</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Ingredients</li>
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
                    <div class="card radius-10 border-start border-0 border-3 border-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Basic Ingredients</p>
                                    <h5 class="my-1 text-success">{{ $stats['basic'] }}</h5>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                    <i class='bx bx-basket'></i>
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
                                    <p class="mb-0 text-secondary">Fruit Ingredients</p>
                                    <h5 class="my-1 text-warning">{{ $stats['fruit'] }}</h5>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto">
                                    <i class='bx bx-apple'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Ingredients</p>
                                    <h5 class="my-1 text-info">{{ $stats['total'] }}</h5>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                                    <i class='bx bx-food-menu'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                        <div class="ms-auto"><a href="{{ route('admin.ingredients.create') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Add New Ingredient</a></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>Ingredient ID</th>
                                <th>Ingredient Name</th>
                                <th>Type</th>
                                <th>Used in Dishes</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($ingredients as $ingredient)
                            <tr>
                                <td>{{ $ingredient->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @if($ingredient->type == 'fruit')
                                                <div class="widget-icon widget-icon-2 rounded-circle text-white bg-gradient-orange">
                                                    <i class='bx bx-apple'></i>
                                                </div>
                                            @else
                                                <div class="widget-icon widget-icon-2 rounded-circle text-white bg-gradient-ohhappiness">
                                                    <i class='bx bx-basket'></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $ingredient->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge
                                        @if($ingredient->type == 'basic') bg-success
                                        @else bg-warning @endif">
                                        {{ ucfirst($ingredient->type) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $ingredient->dishes()->count() }} dishes</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('admin.ingredients.edit', $ingredient->id) }}"
                                           class="btn btn-sm btn-primary"
                                           title="Edit">
                                            <i class='bx bxs-edit'></i>
                                        </a>

                                        <form action="{{ route('admin.ingredients.destroy', $ingredient->id) }}" method="POST" class="ms-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this ingredient? This will remove it from all dishes that use it.')"
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
                                        <i class="bx bx-food-menu" style="font-size: 48px; color: #dee2e6; margin-bottom: 16px;"></i>
                                        <h6 class="text-muted mb-2">No ingredients found</h6>
                                        <p class="text-muted small mb-3">Start by creating your first ingredient</p>
                                        <a href="{{ route('admin.ingredients.create') }}" class="btn btn-primary btn-sm">
                                            <i class="bx bx-plus me-1"></i>Create Ingredient
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
                        {{ $ingredients->links("pagination::bootstrap-4") }}
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--end page wrapper -->

@endsection
