@extends('dashboard.layouts.master')

@section("title", "Coupons")

@section("content")

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">

            <!-- breadcrumb -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Coupons</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Coupons</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end breadcrumb -->

            <!-- Stats Cards -->
            <div class="row mb-2">
                <div class="col-12 col-lg-3">
                    <div class="card radius-10 border-start border-0 border-3 border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Coupons</p>
                                    <h4 class="my-1 text-info">{{ $couponsCount }}</h4>
                                    <p class="mb-0 font-13">All Coupons</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto">
                                    <i class='bx bx-purchase-tag-alt'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="card radius-10 border-start border-0 border-3 border-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Active Coupons</p>
                                    <h4 class="my-1 text-success">{{ $activeCoupons }}</h4>
                                    <p class="mb-0 font-13">Currently Active</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-success text-white ms-auto">
                                    <i class='bx bx-check-circle'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="card radius-10 border-start border-0 border-3 border-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Valid Coupons</p>
                                    <h4 class="my-1 text-warning">{{ $validCoupons }}</h4>
                                    <p class="mb-0 font-13">Active & Not Expired</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-warning text-white ms-auto">
                                    <i class='bx bx-time'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="card radius-10 border-start border-0 border-3 border-danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Expired Coupons</p>
                                    <h4 class="my-1 text-danger">{{ $expiredCoupons }}</h4>
                                    <p class="mb-0 font-13">Past Expiry Date</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-danger text-white ms-auto">
                                    <i class='bx bx-x-circle'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Stats Cards -->

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Coupons Management</h5>
                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Add New Coupon
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>Chef</th>
                                    <th>Discount</th>
                                    <th>Expires At</th>
                                    <th>Status</th>
                                    <th>Usage</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($coupons as $coupon)
                                    <tr>
                                        <td>{{ $coupon->id }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $coupon->code }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($coupon->chef && $coupon->chef->user)
                                                    @if($coupon->chef->user->profile_image)
                                                        <img src="{{ asset('storage/' . $coupon->chef->user->profile_image) }}" alt="Chef" class="rounded-circle me-2" width="40" height="40">
                                                    @else
                                                        <div class="rounded-circle bg-primary text-white me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            {{ strtoupper(substr($coupon->chef->user->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">{{ $coupon->chef->user->name }}</div>
                                                        <small class="text-muted">{{ $coupon->chef->user->email }}</small>
                                                    </div>
                                                @else
                                                    <span class="text-muted">No chef assigned</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($coupon->discount_type === 'percentage')
                                                <span class="badge bg-info">{{ $coupon->discount_value }}%</span>
                                            @else
                                                <span class="badge bg-success">{{ $coupon->discount_value }} EGP</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                {{ $coupon->expires_at->format('Y-m-d H:i') }}
                                                @if($coupon->is_expired)
                                                    <br><small class="text-danger">Expired</small>
                                                @else
                                                    <br><small class="text-success">{{ $coupon->expires_at->diffForHumans() }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($coupon->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <strong>{{ $coupon->usage_count }}</strong>
                                                @if($coupon->total_discounts > 0)
                                                    <br><small class="text-muted">{{ $coupon->total_discounts }} EGP saved</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <!-- View Details Button -->
                                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewCouponModal{{ $coupon->id }}">
                                                    <i class="bx bx-show"></i>
                                                </button>

                                                <!-- Edit Button -->
                                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bx bx-edit"></i>
                                                </a>

                                                <!-- Toggle Status Button -->
                                                <form action="{{ route('admin.coupons.toggle-status', $coupon->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-{{ $coupon->is_active ? 'warning' : 'success' }}" onclick="return confirm('Are you sure you want to {{ $coupon->is_active ? 'deactivate' : 'activate' }} this coupon?')">
                                                        <i class="bx bx-{{ $coupon->is_active ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>

                                                <!-- Delete Button -->
                                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this coupon?')">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bx bx-purchase-tag-alt display-6"></i>
                                                <p class="mt-2">No coupons found</p>
                                                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                                                    <i class="bx bx-plus"></i> Add First Coupon
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $coupons->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Coupon Modals -->
    @foreach ($coupons as $coupon)
        <div class="modal fade" id="viewCouponModal{{ $coupon->id }}" tabindex="-1" aria-labelledby="viewCouponModalLabel{{ $coupon->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewCouponModalLabel{{ $coupon->id }}">Coupon Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Basic Information</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Code:</strong></td>
                                        <td><span class="badge bg-secondary">{{ $coupon->code }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Discount Type:</strong></td>
                                        <td>{{ ucfirst($coupon->discount_type) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Discount Value:</strong></td>
                                        <td>
                                            @if($coupon->discount_type === 'percentage')
                                                <span class="badge bg-info">{{ $coupon->discount_value }}%</span>
                                            @else
                                                <span class="badge bg-success">{{ $coupon->discount_value }} EGP</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            @if($coupon->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Expires At:</strong></td>
                                        <td>
                                            {{ $coupon->expires_at->format('Y-m-d H:i') }}
                                            @if($coupon->is_expired)
                                                <br><small class="text-danger">Expired</small>
                                            @else
                                                <br><small class="text-success">{{ $coupon->expires_at->diffForHumans() }}</small>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Chef & Usage Information</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Chef:</strong></td>
                                        <td>
                                            @if($coupon->chef && $coupon->chef->user)
                                                <div class="d-flex align-items-center">
                                                    @if($coupon->chef->user->profile_image)
                                                        <img src="{{ asset('storage/' . $coupon->chef->user->profile_image) }}" alt="Chef" class="rounded-circle me-2" width="30" height="30">
                                                    @else
                                                        <div class="rounded-circle bg-primary text-white me-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                                            {{ strtoupper(substr($coupon->chef->user->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    {{ $coupon->chef->user->name }}
                                                </div>
                                            @else
                                                <span class="text-muted">No chef assigned</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Usage:</strong></td>
                                        <td>{{ $coupon->usage_count }} times</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Discounts:</strong></td>
                                        <td>{{ $coupon->total_discounts }} EGP</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created At:</strong></td>
                                        <td>{{ $coupon->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if($coupon->description)
                            <div class="mt-3">
                                <h6>Description</h6>
                                <p class="text-muted">{{ $coupon->description }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-primary">Edit Coupon</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
