@extends('dashboard.layouts.master')

@section("title", "Admins")

@section("content")

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">

            <!-- breadcrumb -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Admins</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Admins</li>
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
                                    <p class="mb-0 text-secondary">Total Admins</p>
                                    <h4 class="my-1 text-info">{{ isset($admins) ? $admins->count() : 0 }}</h4>
                                    <p class="mb-0 font-13">System administrators</p>
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
                                    <p class="mb-0 text-secondary">Active Admins</p>
                                    <h4 class="my-1 text-success">{{ isset($admins) ? $admins->where('status', 'active')->count() : 0 }}</h4>
                                    <p class="mb-0 font-13">Currently active</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                    <i class='bx bxs-check-circle'></i>
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
                            <a href="{{ route('admin.admins.create') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0">
                                <i class="bx bxs-plus-square"></i>Add New Admin
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table mb-0" id="adminsTable">
                            <thead class="table-light">
                            <tr>
                                <th>Admin Info</th>
                                <th>Contact</th>
                                <th>Roles & Permissions</th>
                                <th>Status</th>
                                <th>Last Login</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($admins) && $admins->count() > 0)
                                @foreach($admins as $admin)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="recent-product-img">
                                                    <img src="{{ asset('storage/' . $admin->user->profile_image) ?? asset('assets/images/avatars/avatar-1.png') }}"
                                                         alt="{{ $admin->user->name }}"
                                                         class="rounded-circle"
                                                         width="40"
                                                         height="40">
                                                </div>
                                                <div class="ms-2">
                                                    <h6 class="mb-1 font-14">{{ $admin->user->name }}</h6>
                                                    <p class="mb-0 font-13 text-secondary">ID: {{ $admin->id }}</p>
                                                    @if($admin->user->bio)
                                                        <small class="text-muted">{{ Str::limit($admin->user->bio, 30) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <p class="mb-1 font-13">
                                                    <i class="bx bx-envelope me-1 text-primary"></i>
                                                    {{ $admin->user->email }}
                                                </p>
                                                @if($admin->user->phone)
                                                    <p class="mb-0 font-13">
                                                        <i class="bx bx-phone me-1 text-success"></i>
                                                        {{ $admin->user->phone }}
                                                    </p>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                             <div class="permissions-container">
                                                 @if($admin && $admin->roles && $admin->roles->count() > 0)
                                                     <div class="d-flex flex-wrap gap-1 mb-2">
                                                         @foreach($admin->getRoleNames() as $roleName)
                                                             <span class="badge bg-light-primary text-primary border border-primary">
                                                                 <i class="bx bxs-user-badge me-1"></i>{{ $roleName }}
                                                             </span>
                                                         @endforeach
                                                     </div>
                                                     <small class="text-muted">
                                                         {{ $admin->getAllPermissions('admin')->count() }} permissions
                                                     </small>
                                                 @else
                                                     <div class="text-center">
                                                         <i class="bx bx-shield-x text-muted"></i>
                                                         <p class="text-muted mb-0 small">No roles assigned</p>
                                                     </div>
                                                 @endif
                                             </div>
                                         </td>
                                        <td>
                                            @php
                                                $status = $admin->status ?? 'active';
                                                $statusClass = $status === 'active' ? 'success' : ($status === 'inactive' ? 'warning' : 'danger');
                                            @endphp
                                            <div class="badge rounded-pill text-{{ $statusClass }} bg-light-{{ $statusClass }} p-2 text-uppercase px-3">
                                                <i class='bx bxs-circle me-1'></i>{{ ucfirst($status) }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($admin->last_login_at)
                                                <p class="mb-0 font-13">{{ $admin->last_login_at->diffForHumans() }}</p>
                                                <small class="text-muted">{{ $admin->last_login_at->format('M d, Y H:i') }}</small>
                                            @else
                                                <p class="text-muted mb-0">Never logged in</p>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <!-- View Button -->
                                                <button type="button"
                                                        class="btn btn-sm btn-info"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewAdminModal{{ $admin->id }}"
                                                        title="View Details">
                                                    <i class='bx bx-show'></i>
                                                </button>

                                                <!-- Edit Button -->
                                                <a href="{{ route('admin.admins.edit', $admin->id) }}"
                                                   class="btn btn-sm btn-primary"
                                                   title="Edit">
                                                    <i class='bx bxs-edit'></i>
                                                </a>

                                                <!-- Delete Button -->
                                                <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this admin?')"
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
                                    <td colspan="7" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bx bx-user-x display-1 text-muted mb-3"></i>
                                            <h5 class="text-muted">No Admins Found</h5>
                                            <p class="text-muted">No administrators have been added yet.</p>
                                            <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
                                                <i class="bx bx-plus me-1"></i>Add First Admin
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(isset($admins) && method_exists($admins, 'links'))
                        <div class="mt-4">
                            {{ $admins->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Admin Details Modals -->
            @if(isset($admins) && $admins->count() > 0)
                @foreach($admins as $admin)
                    <div class="modal fade" id="viewAdminModal{{ $admin->id }}" tabindex="-1" aria-labelledby="viewAdminModalLabel{{ $admin->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="viewAdminModalLabel{{ $admin->id }}">
                                        <i class="bx bxs-user-detail me-2"></i>Admin Details: {{ $admin->user->name }}
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4 text-center mb-3">
                                            <img src="{{ asset('storage/' . $admin->user->profile_image) ?? asset('assets/images/avatars/avatar-1.png') }}"
                                                 alt="{{ $admin->user->name }}"
                                                 class="rounded-circle mb-3"
                                                 width="120"
                                                 height="120">
                                            <h6>{{ $admin->user->name }}</h6>
                                            <p class="text-muted">{{ $admin->user->email }}</p>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-primary">
                                                <i class="bx bx-info-circle me-1"></i>Basic Information
                                            </h6>
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Admin ID:</strong></td>
                                                    <td>{{ $admin->id }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Name:</strong></td>
                                                    <td>{{ $admin->user->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Email:</strong></td>
                                                    <td>{{ $admin->user->email }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Phone:</strong></td>
                                                    <td>{{ $admin->user->phone ?? 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Status:</strong></td>
                                                    <td>
                                                        @php $status = $admin->status ?? 'active'; @endphp
                                                        <span class="badge bg-{{ $status === 'active' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Joined:</strong></td>
                                                    <td>{{ $admin->created_at ? $admin->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Last Login:</strong></td>
                                                    <td>{{ $admin->last_login_at ? $admin->last_login_at->format('M d, Y H:i') : 'Never' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    @if($admin->user->bio)
                                        <hr>
                                        <h6 class="text-primary">
                                            <i class="bx bx-user me-1"></i>Bio
                                        </h6>
                                        <p>{{ $admin->user->bio }}</p>
                                    @endif

                                    <hr>
                                    <h6 class="text-primary">
                                        <i class="bx bxs-shield me-1"></i>Roles & Permissions
                                    </h6>
                                    @if($admin && $admin->roles && $admin->roles->count() > 0)
                                        <div class="permissions-section">
                                            @foreach($admin->roles as $role)
                                                <div class="mb-3 p-3 border rounded">
                                                    <h6 class="text-info">
                                                        <i class="bx bxs-user-badge me-1"></i>{{ $role->name }}
                                                    </h6>
                                                    @if($role->permissions && $role->permissions->count() > 0)
                                                        <div class="d-flex flex-wrap gap-1">
                                                            @foreach($role->permissions as $permission)
                                                                <span class="badge bg-light text-dark border">{{ $permission->name }}</span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <small class="text-muted">No specific permissions</small>
                                                    @endif
                                                </div>
                                            @endforeach
                                            <div class="alert alert-info mt-3">
                                                <i class="bx bx-info-circle me-2"></i>
                                                This admin has <strong>{{ $admin->getAllPermissions('admin')->count() }}</strong> total permissions.
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="bx bx-shield-x me-2"></i>
                                            No roles or permissions assigned to this admin.
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ route('admin.admins.edit', $admin->id) }}" class="btn btn-primary">
                                        <i class="bx bxs-edit me-1"></i>Edit Admin
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
        .permissions-container {
            min-width: 200px;
            max-width: 300px;
        }

        .permissions-container .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }

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

        .permissions-section {
            max-height: 300px;
            overflow-y: auto;
        }

        @media (max-width: 768px) {
            .permissions-container {
                min-width: auto;
                max-width: none;
            }

            .d-flex.gap-2 {
                flex-direction: column;
                align-items: stretch;
            }

            .d-flex.gap-2 .btn {
                margin-bottom: 0.25rem;
            }
        }
    </style>

    <!-- Custom JavaScript -->
    <script>
        // Select All functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.admin-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });



        // Toggle admin status
        function toggleAdminStatus(adminId, status) {
            if (confirm(`Are you sure you want to ${status} this admin?`)) {
                // Here you would make an AJAX call to update the status
                // For now, we'll just show an alert
                alert(`Admin status would be changed to ${status}`);
                // window.location.reload();
            }
        }

        // Bulk actions
        function bulkAction(action) {
            const checkedBoxes = document.querySelectorAll('.admin-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('Please select at least one admin');
                return;
            }

            const adminIds = Array.from(checkedBoxes).map(cb => cb.value);
            if (confirm(`Are you sure you want to ${action} ${adminIds.length} admin(s)?`)) {
                // Implement bulk action logic here
                alert(`Bulk ${action} would be performed on ${adminIds.length} admin(s)`);
            }
        }
    </script>

@endsection
