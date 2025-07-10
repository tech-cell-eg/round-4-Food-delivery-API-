@extends('dashboard.layouts.master')

@section("title", "Dishes")

@section("content")

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">

            <!-- breadcrumb -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Dishes</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Dishes</li>
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
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Breakfast Dishes</p>
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
                                    <p class="mb-0 text-secondary">Lunch Dishes</p>
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
                                    <p class="mb-0 text-secondary">Dinner Dishes</p>
                                    <h5 class="my-1 text-danger">{{ $stats['dinner'] }}</h5>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                                    <i class='bx bxs-moon'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Available Dishes</p>
                                    <h5 class="my-1 text-success">{{ $stats['available'] }}</h5>
                                    <p class="mb-0 text-muted small">Total: {{ $stats['total'] }}</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                    <i class='bx bx-check-circle'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                        <div class="ms-auto"><a href="{{ route('admin.dishes.create') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Add New Dish</a></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>Dish No</th>
                                <th>Dish Image</th>
                                <th>Dish Name</th>
                                <th>Chef</th>
                                <th>Category</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($dishes as $dish)
                            <tr>
                                <td>{{ $dish->id }}</td>
                                <td>
                                    @if($dish->image && \Storage::disk('public')->exists($dish->image))
                                        <img src="{{ asset('storage/' . $dish->image) }}"
                                             alt="{{ $dish->name }}"
                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;"
                                             loading="lazy"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="d-none align-items-center justify-content-center"
                                             style="width: 60px; height: 60px; background-color: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6;">
                                            <i class="bx bx-dish" style="font-size: 24px; color: #6c757d;"></i>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center justify-content-center"
                                             style="width: 60px; height: 60px; background-color: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6;">
                                            <i class="bx bx-dish" style="font-size: 24px; color: #6c757d;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-0">{{ $dish->name }}</h6>
                                        <p class="mb-0 text-muted small">{{ Str::limit($dish->description, 30) }}</p>
                                    </div>
                                </td>
                                <td>
                                    @if($dish->chef)
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                @if($dish->chef->user->profile_image && \Storage::disk('public')->exists($dish->chef->user->profile_image))
                                                    <img src="{{ asset($dish->chef->user->profile_image) }}"
                                                         alt="{{ $dish->chef->user->name }}"
                                                         style="width: 30px; height: 30px; object-fit: cover; border-radius: 50%;">
                                                @else
                                                    <div class="widget-icon rounded-circle text-white bg-gradient-info"
                                                         style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                                        {{ strtoupper(substr($dish->chef->user->name, 0, 2)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <small class="text-muted">{{ $dish->chef->user->name }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">No Chef</span>
                                    @endif
                                </td>
                                <td>
                                    @if($dish->category)
                                        <span class="badge
                                            @if($dish->category->meal_type == 'breakfast') bg-info
                                            @elseif($dish->category->meal_type == 'lunch') bg-warning
                                            @else bg-danger @endif">
                                            {{ $dish->category->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">No Category</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($dish->avg_rate))
                                                    <i class="bx bxs-star text-warning"></i>
                                                @elseif($i == ceil($dish->avg_rate) && $dish->avg_rate - floor($dish->avg_rate) >= 0.5)
                                                    <i class="bx bxs-star-half text-warning"></i>
                                                @else
                                                    <i class="bx bx-star text-muted"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <small class="text-muted">({{ number_format($dish->avg_rate, 1) }})</small>
                                    </div>
                                    <small class="text-muted">{{ $dish->total_rate }} reviews</small>
                                </td>
                                <td>
                                    @if($dish->is_available)
                                        <span class="badge bg-success">Available</span>
                                    @else
                                        <span class="badge bg-secondary">Unavailable</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <!-- View Button -->
                                        <button type="button"
                                                class="btn btn-sm btn-info"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewDishModal{{ $dish->id }}"
                                                title="View Details">
                                            <i class='bx bx-show'></i>
                                        </button>

                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.dishes.edit', $dish->id) }}"
                                           class="btn btn-sm btn-primary"
                                           title="Edit">
                                            <i class='bx bxs-edit'></i>
                                        </a>

                                        <!-- Delete Button -->
                                        <form action="{{ route('admin.dishes.destroy', $dish->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this dish?')"
                                                    title="Delete">
                                                <i class='bx bxs-trash'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bx bx-dish" style="font-size: 48px; color: #dee2e6; margin-bottom: 16px;"></i>
                                        <h6 class="text-muted mb-2">No dishes found</h6>
                                        <p class="text-muted small mb-3">Start by creating your first dish</p>
                                        <a href="{{ route('admin.dishes.create') }}" class="btn btn-primary btn-sm">
                                            <i class="bx bx-plus me-1"></i>Create Dish
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
                        {{ $dishes->links("pagination::bootstrap-4") }}
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--end page wrapper -->

            <!-- Dish Details Modals -->
            @foreach($dishes as $dish)
                <div class="modal fade" id="viewDishModal{{ $dish->id }}" tabindex="-1" aria-labelledby="viewDishModalLabel{{ $dish->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="viewDishModalLabel{{ $dish->id }}">
                                    <i class="bx bx-dish me-2"></i>Dish Details: {{ $dish->name }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="dish-image-container mb-3">
                                            @if($dish->image && \Storage::disk('public')->exists($dish->image))
                                                <img src="{{ asset('storage/' . $dish->image) }}"
                                                     alt="{{ $dish->name }}"
                                                     class="img-fluid rounded shadow-sm"
                                                     style="width: 100%; height: 250px; object-fit: cover;"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="d-none align-items-center justify-content-center bg-light rounded"
                                                     style="width: 100%; height: 250px;">
                                                    <i class="bx bx-dish" style="font-size: 48px; color: #6c757d;"></i>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center justify-content-center bg-light rounded"
                                                     style="width: 100%; height: 250px;">
                                                    <i class="bx bx-dish" style="font-size: 48px; color: #6c757d;"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Rating -->
                                        <div class="text-center">
                                            <div class="d-flex justify-content-center align-items-center mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($dish->avg_rate))
                                                        <i class="bx bxs-star text-warning"></i>
                                                    @elseif($i == ceil($dish->avg_rate) && $dish->avg_rate - floor($dish->avg_rate) >= 0.5)
                                                        <i class="bx bxs-star-half text-warning"></i>
                                                    @else
                                                        <i class="bx bx-star text-muted"></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-2 text-muted">({{ number_format($dish->avg_rate, 1) }}/5)</span>
                                            </div>
                                            <p class="text-muted small">{{ $dish->total_rate }} reviews</p>
                                        </div>
                                    </div>

                                    <div class="col-md-7">
                                        <h6 class="text-primary">
                                            <i class="bx bx-info-circle me-1"></i>Basic Information
                                        </h6>
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Dish ID:</strong></td>
                                                <td>{{ $dish->id }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Name:</strong></td>
                                                <td>{{ $dish->name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td>
                                                    @if($dish->is_available)
                                                        <span class="badge bg-success">Available</span>
                                                    @else
                                                        <span class="badge bg-secondary">Unavailable</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Chef:</strong></td>
                                                <td>
                                                    @if($dish->chef)
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-2">
                                                                @if($dish->chef->user->profile_image && \Storage::disk('public')->exists($dish->chef->user->profile_image))
                                                                    <img src="{{ asset($dish->chef->user->profile_image) }}"
                                                                         alt="{{ $dish->chef->user->name }}"
                                                                         style="width: 30px; height: 30px; object-fit: cover; border-radius: 50%;">
                                                                @else
                                                                    <div class="widget-icon rounded-circle text-white bg-gradient-info d-flex align-items-center justify-content-center"
                                                                         style="width: 30px; height: 30px; font-size: 12px;">
                                                                        {{ strtoupper(substr($dish->chef->user->name, 0, 2)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <span>{{ $dish->chef->user->name }}</span>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">No Chef</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Category:</strong></td>
                                                <td>
                                                    @if($dish->category)
                                                        <span class="badge
                                                            @if($dish->category->meal_type == 'breakfast') bg-info
                                                            @elseif($dish->category->meal_type == 'lunch') bg-warning
                                                            @else bg-danger @endif">
                                                            {{ $dish->category->name }} ({{ $dish->category->meal_type }})
                                                        </span>
                                                    @else
                                                        <span class="text-muted">No Category</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <!-- Description -->
                                @if($dish->description)
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h6 class="text-primary">
                                            <i class="bx bx-text me-1"></i>Description
                                        </h6>
                                        <p class="text-muted">{{ $dish->description }}</p>
                                    </div>
                                </div>
                                @endif

                                <!-- Sizes & Prices -->
                                @if($dish->sizes && $dish->sizes->count() > 0)
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h6 class="text-primary">
                                            <i class="bx bx-dollar me-1"></i>Available Sizes & Prices
                                        </h6>
                                        <div class="row">
                                            @foreach($dish->sizes as $size)
                                                <div class="col-md-4 col-6 mb-2">
                                                    <div class="border rounded p-2 text-center">
                                                        <strong>{{ $size->size }}</strong><br>
                                                        <span class="text-primary">${{ $size->price }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Ingredients -->
                                @if($dish->ingredients && $dish->ingredients->count() > 0)
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h6 class="text-primary">
                                            <i class="bx bx-basket me-1"></i>Ingredients
                                        </h6>
                                        <div class="d-flex flex-wrap">
                                            @foreach($dish->ingredients as $ingredient)
                                                <span class="badge bg-light text-dark me-1 mb-1 border">{{ $ingredient->name }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif

                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('admin.dishes.edit', $dish->id) }}" class="btn btn-primary">
                                    <i class="bx bxs-edit me-1"></i>Edit Dish
                                </a>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
    <!--end page wrapper -->

@endsection
