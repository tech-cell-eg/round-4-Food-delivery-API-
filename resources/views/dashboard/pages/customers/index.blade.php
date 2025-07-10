@extends('dashboard.layouts.master')

@section("title", "Customers")

@section("content")

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">

            <!-- breadcrumb -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Customers</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Customers</li>
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
                                    <p class="mb-0 text-secondary">Total Customers</p>
                                    <h4 class="my-1 text-info">{{ $customersCount }}</h4>
                                    <p class="mb-0 font-13">System Customers</p>

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
                                    <p class="mb-0 text-secondary">Verified Accounts</p>
                                    <h4 class="my-1 text-success">{{ $verifiedAccounts }}</h4>
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
                                    <h4 class="my-1 text-success">{{ $activeAccounts }}</h4>
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
                            <a href="{{ route('admin.customers.create') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0">
                                <i class="bx bxs-plus-square"></i>Add New Customer
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table mb-0" id="customersTable">
                            <thead class="table-light">
                            <tr>
                                <th>Customer Info</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($customers) && $customers->count() > 0)
                                @foreach($customers as $customer)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="recent-product-img">
                                                    <img src="{{ asset('storage/' . $customer->user->profile_image) ?? asset('assets/images/avatars/avatar-1.png') }}"
                                                         alt="{{ $customer->user->name }}"
                                                         class="rounded-circle"
                                                         width="40"
                                                         height="40">
                                                </div>
                                                <div class="ms-2">
                                                    <h6 class="mb-1 font-14">{{ $customer->user->name }}</h6>
                                                    <p class="mb-0 font-13 text-secondary">ID: {{ $customer->id }}</p>
                                                    @if($customer->user->bio)
                                                        <small class="text-muted">{{ Str::limit($customer->user->bio, 30) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <p class="mb-1 font-13">
                                                    <i class="bx bx-envelope me-1 text-primary"></i>
                                                    {{ $customer->user->email }}
                                                </p>
                                                @if($customer->user->phone)
                                                    <p class="mb-0 font-13">
                                                        <i class="bx bx-phone me-1 text-success"></i>
                                                        {{ $customer->user->phone }}
                                                    </p>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $status = $customer->status ?? 'active';
                                                $statusClass = $status === 'active' ? 'success' : ($status === 'inactive' ? 'warning' : 'danger');
                                            @endphp
                                            <div class="badge rounded-pill text-{{ $statusClass }} bg-light-{{ $statusClass }} p-2 text-uppercase px-3">
                                                <i class='bx bxs-circle me-1'></i>{{ ucfirst($status) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <!-- View Button -->
                                                <button type="button"
                                                        class="btn btn-sm btn-info"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewCustomerModal{{ $customer->id }}"
                                                        title="View Details">
                                                    <i class='bx bx-show'></i>
                                                </button>

                                                <!-- Edit Button -->
                                                <a href="{{ route('admin.customers.edit', $customer->id) }}"
                                                   class="btn btn-sm btn-primary"
                                                   title="Edit">
                                                    <i class='bx bxs-edit'></i>
                                                </a>

                                                <!-- Delete Button -->
                                                <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this customer?')"
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
                                    <td colspan="4" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bx bx-user-x display-1 text-muted mb-3"></i>
                                            <h5 class="text-muted">No Customers Found</h5>
                                            <p class="text-muted">No customers have been registered yet.</p>
                                            <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
                                                <i class="bx bx-plus me-1"></i>Add First Customer
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(isset($customers) && method_exists($customers, 'links') && $customers->hasPages())
                        <div class="mt-4 d-flex justify-content-between align-items-center">
                            <div class="pagination-info">
                                <p class="text-muted mb-0">
                                    Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} customers
                                </p>
                            </div>
                            <div class="pagination-links">
                                {{ $customers->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Customer Details Modals -->
            @if(isset($customers) && $customers->count() > 0)
                @foreach($customers as $customer)
                    <div class="modal fade" id="viewCustomerModal{{ $customer->id }}" tabindex="-1" aria-labelledby="viewCustomerModalLabel{{ $customer->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="viewCustomerModalLabel{{ $customer->id }}">
                                        <i class="bx bxs-user-detail me-2"></i>Customer Details: {{ $customer->user->name }}
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4 text-center mb-3">
                                            <img src="{{ asset('storage/' . $customer->user->profile_image) ?? asset('assets/images/avatars/avatar-1.png') }}"
                                                 alt="{{ $customer->user->name }}"
                                                 class="rounded-circle mb-3"
                                                 width="120"
                                                 height="120">
                                            <h6>{{ $customer->user->name }}</h6>
                                            <p class="text-muted">{{ $customer->user->email }}</p>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-primary">
                                                <i class="bx bx-info-circle me-1"></i>Basic Information
                                            </h6>
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Customer ID:</strong></td>
                                                    <td>{{ $customer->id }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Name:</strong></td>
                                                    <td>{{ $customer->user->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Email:</strong></td>
                                                    <td>{{ $customer->user->email }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Phone:</strong></td>
                                                    <td>{{ $customer->user->phone ?? 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Status:</strong></td>
                                                    <td>
                                                        @php $status = $customer->status ?? 'active'; @endphp
                                                        <span class="badge bg-{{ $status === 'active' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Joined:</strong></td>
                                                    <td>{{ $customer->created_at ? $customer->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    @if($customer->user->bio)
                                        <hr>
                                        <h6 class="text-primary">
                                            <i class="bx bx-user me-1"></i>Bio
                                        </h6>
                                        <p>{{ $customer->user->bio }}</p>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-primary">
                                        <i class="bx bxs-edit me-1"></i>Edit Customer
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
        }
    </style>

    <!-- Custom JavaScript -->
    <script>
        // Toggle customer status
        function toggleCustomerStatus(customerId, status) {
            if (confirm(`Are you sure you want to ${status} this customer?`)) {
                // Here you would make an AJAX call to update the status
                // For now, we'll just show an alert
                alert(`Customer status would be changed to ${status}`);
                // window.location.reload();
            }
        }
    </script>

@endsection
