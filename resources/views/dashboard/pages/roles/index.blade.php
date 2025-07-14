@extends('dashboard.layouts.master')

@section("title", "Roles")

@section("content")

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">

            <!-- breadcrumb -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Roles</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Roles</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end breadcrumb -->

            <div class="card">
                <div class="card-body">
                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                        <div class="ms-auto"><a href="{{ route('admin.roles.create') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Add New Role</a></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>Role ID</th>
                                <th>Role Name</th>
                                <th>Permissions</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>
                                            <div>
                                                <h6 class="mb-0">{{ $role->name }}</h6>
                                            </div>
                                    </td>
                                    <td>
                                        <div class="permissions-container">
                                            @if($role->permissions && $role->permissions->count() > 0)
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($role->permissions as $permission)
                                                        <span class="badge bg-light-primary text-primary border border-primary">
                                                            <i class="bx bxs-shield-alt-2 me-1"></i>{{ $permission->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                                <small class="text-muted d-block mt-1">
                                                    {{ $role->permissions->count() }} permission{{ $role->permissions->count() !== 1 ? 's' : '' }}
                                                </small>
                                            @else
                                                <div class="text-center py-2">
                                                    <i class="bx bx-shield-x text-muted fs-4"></i>
                                                    <p class="text-muted mb-0 small">No permissions assigned</p>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <!-- View Button -->
                                            <button type="button"
                                                    class="btn btn-sm btn-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewRoleModal{{ $role->id }}"
                                                    title="View Details">
                                                <i class='bx bx-show'></i>
                                            </button>

                                            <!-- Edit Button -->
                                            <a href="{{ route('admin.roles.edit', $role->id) }}"
                                               class="btn btn-sm btn-primary"
                                               title="Edit">
                                                <i class='bx bxs-edit'></i>
                                            </a>

                                            <!-- Delete Button -->
                                            <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this role?')"
                                                        title="Delete">
                                                    <i class='bx bxs-trash'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Role Details Modals -->
            @foreach($roles as $role)
                <div class="modal fade" id="viewRoleModal{{ $role->id }}" tabindex="-1" aria-labelledby="viewRoleModalLabel{{ $role->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="viewRoleModalLabel{{ $role->id }}">
                                    <i class="bx bxs-user-badge me-2"></i>Role Details: {{ $role->name }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-primary">
                                            <i class="bx bx-info-circle me-1"></i>Basic Information
                                        </h6>
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Role ID:</strong></td>
                                                <td>{{ $role->id }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Role Name:</strong></td>
                                                <td>{{ $role->name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Created:</strong></td>
                                                <td>{{ $role->created_at ? $role->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Last Updated:</strong></td>
                                                <td>{{ $role->updated_at ? $role->updated_at->format('M d, Y H:i') : 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-primary">
                                            <i class="bx bxs-shield me-1"></i>Assigned Permissions
                                        </h6>
                                        @if($role->permissions && $role->permissions->count() > 0)
                                            <div class="permissions-list">
                                                @foreach($role->permissions as $permission)
                                                    <div class="d-flex align-items-center mb-2 p-2 rounded bg-light">
                                                        <i class="bx bxs-shield-alt-2 text-primary me-2"></i>
                                                        <div>
                                                            <strong>{{ $permission->name }}</strong>
                                                            @if($permission->description)
                                                                <br><small class="text-muted">{{ $permission->description }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="alert alert-info mt-3">
                                                <i class="bx bx-info-circle me-2"></i>
                                                This role has <strong>{{ $role->permissions->count() }}</strong> permission{{ $role->permissions->count() !== 1 ? 's' : '' }} assigned.
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="bx bx-shield-x me-2"></i>
                                                No permissions are currently assigned to this role.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary">
                                    <i class="bx bxs-edit me-1"></i>Edit Role
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

    <!-- Custom CSS for better appearance -->
    <style>
        .permissions-container {
            min-width: 300px;
            max-width: 400px;
        }

        .permissions-container .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }

        .bg-light-primary {
            background-color: rgba(13, 110, 253, 0.1) !important;
        }

        .permissions-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .table td {
            vertical-align: middle;
        }

        @media (max-width: 768px) {
            .permissions-container {
                min-width: auto;
                max-width: none;
            }

            .permissions-container .badge {
                font-size: 0.7rem;
                padding: 0.25rem 0.5rem;
                margin-bottom: 0.25rem;
            }
        }
    </style>

@endsection
