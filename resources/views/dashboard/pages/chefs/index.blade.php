@extends('dashboard.layouts.master')

@section("title", "Chefs")

@section("content")

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">

            <!-- breadcrumb -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Chefs</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Chefs</li>
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
                                    <p class="mb-0 text-secondary">Total Chefs</p>
                                    <h4 class="my-1 text-info">{{ $chefsCount }}</h4>
                                    <p class="mb-0 font-13">System Chefs</p>

                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto">
                                    <i class='bx bxs-user-detail'></i>
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
                                    <p class="mb-0 text-secondary">Verified Chefs</p>
                                    <h4 class="my-1 text-success">{{ $verifiedChefs }}</h4>
                                    <p class="mb-0 font-13">Currently Verified</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                    <i class='bx bxs-check-circle'></i>
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
                                    <p class="mb-0 text-secondary">Active Accounts</p>
                                    <h4 class="my-1 text-success">{{ $activeChefs }}</h4>
                                    <p class="mb-0 font-13">Currently active</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                    <i class='bx bx-user-check'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Main Content Card -->
            <div class="card">
                <div class="card-body">
                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                        <div class="ms-auto">
                            <a href="{{ route('admin.chefs.create') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0">
                                <i class="bx bxs-plus-square"></i>Add New Chef
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table mb-0" id="chefsTable">
                            <thead class="table-light">
                            <tr>
                                <th>Chef Info</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Balance</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($chefs) && $chefs->count() > 0)
                                @foreach($chefs as $chef)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="recent-product-img">
                                                    <img src="{{ asset('storage/' . $chef->user->profile_image) ?? asset('assets/images/avatars/avatar-1.png') }}"
                                                         alt="{{ $chef->user->name }}"
                                                         class="rounded-circle"
                                                         width="40"
                                                         height="40">
                                                </div>
                                                <div class="ms-2">
                                                    <h6 class="mb-1 font-14">{{ $chef->user->name }}</h6>
                                                    <p class="mb-0 font-13 text-secondary">ID: {{ $chef->id }}</p>
                                                    @if($chef->national_id)
                                                        <small class="text-muted">National ID: {{ $chef->national_id }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <p class="mb-1 font-13">
                                                    <i class="bx bx-envelope me-1 text-primary"></i>
                                                    {{ $chef->user->email }}
                                                </p>
                                                @if($chef->user->phone)
                                                    <p class="mb-0 font-13">
                                                        <i class="bx bx-phone me-1 text-success"></i>
                                                        {{ $chef->user->phone }}
                                                    </p>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                @if($chef->is_verified)
                                                    <div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">
                                                        <i class='bx bxs-circle me-1'></i>Verified
                                                    </div>
                                                @else
                                                    <div class="badge rounded-pill text-warning bg-light-warning p-2 text-uppercase px-3">
                                                        <i class='bx bxs-circle me-1'></i>Unverified
                                                    </div>
                                                @endif
                                                @if($chef->user->email_verified_at)
                                                    <small class="text-success">
                                                        <i class="bx bx-check-circle me-1"></i>Email Verified
                                                    </small>
                                                @else
                                                    <small class="text-warning">
                                                        <i class="bx bx-error-circle me-1"></i>Email Not Verified
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <h6 class="mb-0 text-primary">${{ number_format($chef->balance, 2) }}</h6>
                                                <small class="text-muted">Current Balance</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <!-- View Button -->
                                                <button type="button"
                                                        class="btn btn-sm btn-info"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewChefModal{{ $chef->id }}"
                                                        title="View Details">
                                                    <i class='bx bx-show'></i>
                                                </button>

                                                <!-- Edit Button -->
                                                <a href="{{ route('admin.chefs.edit', $chef->id) }}"
                                                   class="btn btn-sm btn-primary"
                                                   title="Edit">
                                                    <i class='bx bxs-edit'></i>
                                                </a>

                                                <!-- Delete Button -->
                                                <form action="{{ route('admin.chefs.destroy', $chef->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this chef?')"
                                                            title="Delete">
                                                        <i class='bx bxs-trash'></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bx bx-user-x display-1 text-muted mb-3"></i>
                                            <h5 class="text-muted">No Chefs Found</h5>
                                            <p class="text-muted">No chefs have been registered yet.</p>
                                            <a href="{{ route('admin.chefs.create') }}" class="btn btn-primary">
                                                <i class="bx bx-plus me-1"></i>Add First Chef
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(isset($chefs) && method_exists($chefs, 'links') && $chefs->hasPages())
                        <div class="mt-4 d-flex justify-content-between align-items-center">
                            <div class="pagination-info">
                                <p class="text-muted mb-0">
                                    Showing {{ $chefs->firstItem() }} to {{ $chefs->lastItem() }} of {{ $chefs->total() }} chefs
                                </p>
                            </div>
                            <div class="pagination-links">
                                {{ $chefs->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Chef Details Modals -->
            @if(isset($chefs) && $chefs->count() > 0)
                @foreach($chefs as $chef)
                    <div class="modal fade" id="viewChefModal{{ $chef->id }}" tabindex="-1" aria-labelledby="viewChefModalLabel{{ $chef->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="viewChefModalLabel{{ $chef->id }}">
                                        <i class="bx bxs-user-detail me-2"></i>Chef Profile: {{ $chef->user->name }}
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Chef Header Info -->
                                    <div class="row mb-4">
                                        <div class="col-md-3 text-center">
                                            <img src="{{ asset('storage/' . $chef->user->profile_image) ?? asset('assets/images/avatars/avatar-1.png') }}"
                                                 alt="{{ $chef->user->name }}"
                                                 class="rounded-circle mb-3 chef-profile-img"
                                                 width="120"
                                                 height="120">
                                            <h5 class="mb-1">{{ $chef->user->name }}</h5>
                                            <p class="text-muted mb-2">{{ $chef->user->email }}</p>
                                            <span class="badge bg-{{ $chef->is_verified ? 'success' : 'warning' }} badge-lg">
                                                <i class="bx bx-{{ $chef->is_verified ? 'check-circle' : 'error-circle' }} me-1"></i>
                                                {{ $chef->is_verified ? 'Verified Chef' : 'Unverified' }}
                                            </span>
                                        </div>
                                        <div class="col-md-9">
                                            <!-- Stats Cards -->
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <div class="card border-primary h-100">
                                                        <div class="card-body text-center p-3">
                                                            <i class="bx bx-restaurant text-primary display-6"></i>
                                                            <h4 class="text-primary mt-2 mb-1">{{ $chef->dishes_count ?? 0 }}</h4>
                                                            <small class="text-muted">Total Dishes</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card border-success h-100">
                                                        <div class="card-body text-center p-3">
                                                            <i class="bx bx-shopping-bag text-success display-6"></i>
                                                            <h4 class="text-success mt-2 mb-1">{{ $chef->total_orders ?? 0 }}</h4>
                                                            <small class="text-muted">Total Orders</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card border-warning h-100">
                                                        <div class="card-body text-center p-3">
                                                            <i class="bx bx-star text-warning display-6"></i>
                                                            <h4 class="text-warning mt-2 mb-1">{{ number_format($chef->average_rating ?? 0, 1) }}</h4>
                                                            <small class="text-muted">Avg Rating</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card border-info h-100">
                                                        <div class="card-body text-center p-3">
                                                            <i class="bx bx-money text-info display-6"></i>
                                                            <h4 class="text-info mt-2 mb-1">${{ number_format($chef->balance, 0) }}</h4>
                                                            <small class="text-muted">Balance</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Detailed Information Tabs -->
                                    <ul class="nav nav-tabs mb-3" id="chefDetailTabs{{ $chef->id }}" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="basic-tab{{ $chef->id }}" data-bs-toggle="tab" data-bs-target="#basic{{ $chef->id }}" type="button" role="tab">
                                                <i class="bx bx-user me-1"></i>Basic Info
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="contact-tab{{ $chef->id }}" data-bs-toggle="tab" data-bs-target="#contact{{ $chef->id }}" type="button" role="tab">
                                                <i class="bx bx-phone me-1"></i>Contact & Location
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="business-tab{{ $chef->id }}" data-bs-toggle="tab" data-bs-target="#business{{ $chef->id }}" type="button" role="tab">
                                                <i class="bx bx-briefcase me-1"></i>Business Info
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="reviews-tab{{ $chef->id }}" data-bs-toggle="tab" data-bs-target="#reviews{{ $chef->id }}" type="button" role="tab">
                                                <i class="bx bx-message-dots me-1"></i>Reviews
                                            </button>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="chefDetailTabsContent{{ $chef->id }}">
                                        <!-- Basic Information Tab -->
                                        <div class="tab-pane fade show active" id="basic{{ $chef->id }}" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="text-primary mb-3">
                                                        <i class="bx bx-info-circle me-1"></i>Personal Details
                                                    </h6>
                                                    <table class="table table-borderless">
                                                        <tr>
                                                            <td width="40%"><strong>Chef ID:</strong></td>
                                                            <td>{{ $chef->id }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Full Name:</strong></td>
                                                            <td>{{ $chef->user->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Email:</strong></td>
                                                            <td>
                                                                {{ $chef->user->email }}
                                                                @if($chef->user->email_verified_at)
                                                                    <i class="bx bx-check-circle text-success ms-1" title="Verified"></i>
                                                                @else
                                                                    <i class="bx bx-error-circle text-warning ms-1" title="Not Verified"></i>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>National ID:</strong></td>
                                                            <td>{{ $chef->national_id ?? 'Not provided' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Join Date:</strong></td>
                                                            <td>{{ $chef->created_at->format('F d, Y') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Account Age:</strong></td>
                                                            <td>{{ $chef->created_at->diffForHumans() }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-primary mb-3">
                                                        <i class="bx bx-shield-check me-1"></i>Verification Status
                                                    </h6>
                                                    <div class="verification-status">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <i class="bx bx-{{ $chef->is_verified ? 'check' : 'x' }}-circle text-{{ $chef->is_verified ? 'success' : 'danger' }} me-2"></i>
                                                            <span>Chef Status: <strong>{{ $chef->is_verified ? 'Verified' : 'Unverified' }}</strong></span>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-2">
                                                            <i class="bx bx-{{ $chef->user->email_verified_at ? 'check' : 'x' }}-circle text-{{ $chef->user->email_verified_at ? 'success' : 'danger' }} me-2"></i>
                                                            <span>Email: <strong>{{ $chef->user->email_verified_at ? 'Verified' : 'Not Verified' }}</strong></span>
                                                            @if($chef->user->email_verified_at)
                                                                <br><small class="text-muted ms-4">Verified on {{ $chef->user->email_verified_at->format('M d, Y') }}</small>
                                                            @endif
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <i class="bx bx-id-card text-info me-2"></i>
                                                            <span>ID Status: <strong>{{ $chef->national_id ? 'Provided' : 'Missing' }}</strong></span>
                                                        </div>
                                                    </div>

                                                    @if($chef->user->bio)
                                                        <h6 class="text-primary mb-2 mt-4">
                                                            <i class="bx bx-user me-1"></i>Biography
                                                        </h6>
                                                        <p class="text-muted">{{ $chef->user->bio }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Contact & Location Tab -->
                                        <div class="tab-pane fade" id="contact{{ $chef->id }}" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="text-primary mb-3">
                                                        <i class="bx bx-phone me-1"></i>Contact Information
                                                    </h6>
                                                    <table class="table table-borderless">
                                                        <tr>
                                                            <td width="30%"><strong>Phone:</strong></td>
                                                            <td>{{ $chef->user->phone ?? 'Not provided' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Email:</strong></td>
                                                            <td>{{ $chef->user->email }}</td>
                                                        </tr>
                                                    </table>

                                                    @if($chef->user->address)
                                                        <h6 class="text-primary mb-3 mt-4">
                                                            <i class="bx bx-home me-1"></i>Address
                                                        </h6>
                                                        <p>{{ $chef->user->address }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    @if($chef->location || $chef->user->latitude || $chef->user->longitude)
                                                        <h6 class="text-primary mb-3">
                                                            <i class="bx bx-map me-1"></i>Location Details
                                                        </h6>
                                                        @if($chef->location)
                                                            <p><strong>Service Area:</strong> {{ $chef->location }}</p>
                                                        @endif
                                                        @if($chef->user->latitude && $chef->user->longitude)
                                                            <p><strong>Coordinates:</strong></p>
                                                            <ul class="list-unstyled ms-3">
                                                                <li>Latitude: {{ $chef->user->latitude }}</li>
                                                                <li>Longitude: {{ $chef->user->longitude }}</li>
                                                            </ul>
                                                            <a href="https://maps.google.com/?q={{ $chef->user->latitude }},{{ $chef->user->longitude }}" 
                                                               target="_blank" class="btn btn-outline-primary btn-sm">
                                                                <i class="bx bx-map me-1"></i>View on Google Maps
                                                            </a>
                                                        @endif
                                                    @else
                                                        <div class="text-center text-muted">
                                                            <i class="bx bx-map display-4"></i>
                                                            <p>No location information available</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Business Information Tab -->
                                        <div class="tab-pane fade" id="business{{ $chef->id }}" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="text-primary mb-3">
                                                        <i class="bx bx-briefcase me-1"></i>Business Statistics
                                                    </h6>
                                                    <div class="row g-3 mb-4">
                                                        <div class="col-6">
                                                            <div class="text-center p-3 border rounded">
                                                                <h5 class="text-primary mb-1">{{ $chef->dishes_count ?? 0 }}</h5>
                                                                <small class="text-muted">Total Dishes</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="text-center p-3 border rounded">
                                                                <h5 class="text-success mb-1">{{ $chef->reviews_count ?? 0 }}</h5>
                                                                <small class="text-muted">Total Reviews</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="text-center p-3 border rounded">
                                                                <h5 class="text-warning mb-1">{{ number_format($chef->average_rating ?? 0, 1) }}/5</h5>
                                                                <small class="text-muted">Average Rating</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="text-center p-3 border rounded">
                                                                <h5 class="text-info mb-1">{{ $chef->total_orders ?? 0 }}</h5>
                                                                <small class="text-muted">Total Orders</small>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <h6 class="text-primary mb-3">
                                                        <i class="bx bx-money me-1"></i>Financial Information
                                                    </h6>
                                                    <table class="table table-borderless">
                                                        <tr>
                                                            <td><strong>Current Balance:</strong></td>
                                                            <td class="text-primary fw-bold">${{ number_format($chef->balance, 2) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Total Earnings:</strong></td>
                                                            <td class="text-success fw-bold">${{ number_format($chef->total_earnings ?? 0, 2) }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    @if($chef->description)
                                                        <h6 class="text-primary mb-3">
                                                            <i class="bx bx-file-text me-1"></i>Chef Description
                                                        </h6>
                                                        <div class="p-3 bg-light rounded">
                                                            <p class="mb-0">{{ $chef->description }}</p>
                                                        </div>
                                                    @else
                                                        <div class="text-center text-muted">
                                                            <i class="bx bx-file-text display-4"></i>
                                                            <p>No description provided</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Reviews Tab -->
                                        <div class="tab-pane fade" id="reviews{{ $chef->id }}" role="tabpanel">
                                            @if($chef->reviews_count > 0)
                                                <div class="row mb-4">
                                                    <div class="col-md-4 text-center">
                                                        <h2 class="text-warning mb-1">{{ number_format($chef->average_rating ?? 0, 1) }}</h2>
                                                        <div class="text-warning mb-2">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= floor($chef->average_rating ?? 0))
                                                                    <i class="bx bxs-star"></i>
                                                                @elseif($i - 0.5 <= ($chef->average_rating ?? 0))
                                                                    <i class="bx bxs-star-half"></i>
                                                                @else
                                                                    <i class="bx bx-star"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                        <small class="text-muted">Based on {{ $chef->reviews_count }} reviews</small>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <!-- Rating Breakdown would go here if needed -->
                                                        <p class="text-muted mb-0">Recent customer feedback shows {{ $chef->is_verified ? 'verified' : 'unverified' }} chef status with professional service.</p>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-center text-muted py-5">
                                                    <i class="bx bx-message-dots display-4"></i>
                                                    <h5 class="mt-3">No Reviews Yet</h5>
                                                    <p>This chef hasn't received any reviews yet.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ route('admin.chefs.edit', $chef->id) }}" class="btn btn-primary">
                                        <i class="bx bxs-edit me-1"></i>Edit Chef
                                    </a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>
    <!--end page wrapper -->

    <!-- Custom CSS -->
    <style>
        .bg-light-primary {
            background-color: rgba(13, 110, 253, 0.1) !important;
        }

        .bg-light-success {
            background-color: rgba(25, 135, 84, 0.1) !important;
        }

        .bg-light-warning {
            background-color: rgba(255, 193, 7, 0.1) !important;
        }

        .bg-light-danger {
            background-color: rgba(220, 53, 69, 0.1) !important;
        }

        .table td {
            vertical-align: middle;
        }

        .recent-product-img img {
            border: 2px solid #e0e0e0;
        }

        /* Chef Modal Styles */
        .chef-profile-img {
            border: 4px solid #ffffff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            object-fit: cover;
        }

        .badge-lg {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .modal-xl .modal-content {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .modal-header {
            border-radius: 15px 15px 0 0;
            border-bottom: none;
            padding: 1.5rem;
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 10px;
            margin-right: 5px;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            border: none;
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .nav-tabs .nav-link.active {
            background-color: #0d6efd;
            color: white;
            border: none;
        }

        .verification-status .d-flex {
            padding: 0.5rem;
            border-radius: 8px;
            background-color: #f8f9fa;
            margin-bottom: 0.5rem;
        }

        .verification-status .d-flex:last-child {
            margin-bottom: 0;
        }

        .card.border-primary .card-body {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.05), rgba(13, 110, 253, 0.1));
        }

        .card.border-success .card-body {
            background: linear-gradient(135deg, rgba(25, 135, 84, 0.05), rgba(25, 135, 84, 0.1));
        }

        .card.border-warning .card-body {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.05), rgba(255, 193, 7, 0.1));
        }

        .card.border-info .card-body {
            background: linear-gradient(135deg, rgba(13, 202, 240, 0.05), rgba(13, 202, 240, 0.1));
        }

        .tab-content {
            padding: 1.5rem 0;
        }

        .display-6 {
            font-size: 2rem;
        }

        /* Star Rating Styles */
        .text-warning i {
            font-size: 1.2rem;
        }

        /* Google Maps Button */
        .btn-outline-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
        }

        /* Custom Pagination Styles */
        .pagination-links .pagination {
            margin-bottom: 0;
        }
        
        .pagination-links .page-link {
            border: 1px solid #dee2e6;
            color: #6c757d;
            padding: 0.5rem 0.75rem;
            border-radius: 0.25rem;
            margin: 0 0.125rem;
        }
        
        .pagination-links .page-link:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
            color: #495057;
        }
        
        .pagination-links .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        
        .pagination-links .page-item.disabled .page-link {
            color: #adb5bd;
            background-color: white;
            border-color: #dee2e6;
        }
        
        .pagination-info {
            font-size: 0.875rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .d-flex.gap-2 {
                flex-direction: column;
                align-items: stretch;
            }

            .d-flex.gap-2 .btn {
                margin-bottom: 0.25rem;
            }
            
            .mt-4.d-flex {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .pagination-info {
                order: 2;
            }
            
            .pagination-links {
                order: 1;
                display: flex;
                justify-content: center;
            }

            .modal-xl .modal-dialog {
                margin: 0.5rem;
                max-width: none;
            }

            .chef-profile-img {
                width: 80px !important;
                height: 80px !important;
            }

            .row.g-3 .col-md-3 {
                margin-bottom: 1rem;
            }

            .nav-tabs {
                flex-wrap: wrap;
            }

            .nav-tabs .nav-link {
                font-size: 0.875rem;
                margin-bottom: 5px;
            }
        }

        /* Animation Effects */
        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .btn {
            transition: all 0.3s ease;
        }

        /* Loading Animation for Tabs */
        .tab-pane {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <!-- Custom JavaScript -->
    <script>
        // Toggle chef status
        function toggleChefStatus(chefId, status) {
            if (confirm(`Are you sure you want to ${status} this chef?`)) {
                // Here you would make an AJAX call to update the status
                // For now, we'll just show an alert
                alert(`Chef status would be changed to ${status}`);
                // window.location.reload();
            }
        }
    </script>

@endsection
