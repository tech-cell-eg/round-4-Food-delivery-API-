@extends('dashboard.layouts.master')

@section("title", "Edit Role")

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
                            <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Role</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end breadcrumb -->

            <div class="row">
                <div class="col-xl-9 mx-auto">
                    <div class="card border-top border-0 border-4 border-warning">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <div><i class="bx bxs-edit me-1 font-22 text-warning"></i></div>
                                <h5 class="mb-0 text-warning">Edit Role: {{ $role->name }}</h5>
                            </div>
                            <hr>

                            <!-- Display Validation Errors -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Role Edit Form -->
                            <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" class="row g-3">
                                @csrf
                                @method('PUT')

                                <div class="col-12">
                                    <label for="name" class="form-label">Role Name *</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $role->name) }}"
                                           placeholder="Enter role name (e.g., Admin, Manager, Editor)"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Use descriptive names like Admin, Manager, Editor, or Customer Support</small>
                                </div>


                                <!-- Permissions Section -->
                                <div class="col-12">
                                    <hr>
                                    <h6 class="text-warning mb-3">
                                        <i class="bx bxs-shield me-1"></i>Assign Permissions
                                    </h6>

                                    @if(isset($permissions) && count($permissions) > 0)
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="select_all">
                                                    <label class="form-check-label fw-bold" for="select_all">
                                                        Select All Permissions
                                                    </label>
                                                </div>
                                                <hr>
                                            </div>

                                            @foreach($permissions as $permission)
                                                <div class="col-md-6 col-lg-4 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox"
                                                               type="checkbox"
                                                               name="permissions[]"
                                                               value="{{ $permission->name }}"
                                                               id="permission_{{ $permission->id }}"
                                                               {{
                                                                   in_array($permission->name, old('permissions', $rolePermissions ?? []))
                                                                   ? 'checked'
                                                                   : ''
                                                               }}>
                                                        <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="bx bx-info-circle me-1"></i>
                                            No permissions available. <a href="{{ route('admin.permissions.create') }}">Create permissions first</a>.
                                        </div>
                                    @endif
                                </div>

                                <div class="col-12">
                                    <hr>
                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-warning px-5">
                                            <i class="bx bxs-save me-1"></i>Update Role
                                        </button>
                                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light px-5">
                                            <i class="bx bx-x me-1"></i>Cancel
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Role Information Card -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h6 class="card-title text-info">
                                <i class="bx bx-info-circle me-1"></i>Role Information
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Role ID:</strong> {{ $role->id }}<br>
                                    <strong>Created:</strong> {{ $role->created_at ? $role->created_at->format('M d, Y H:i') : 'N/A' }}<br>
                                    <strong>Last Updated:</strong> {{ $role->updated_at ? $role->updated_at->format('M d, Y H:i') : 'N/A' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Current Permissions:</strong>
                                    @if(isset($rolePermissions) && count($rolePermissions) > 0)
                                        <div class="mt-2">
                                            @foreach($permissions as $permission)
                                                @if(in_array($permission->id, $rolePermissions))
                                                    <span class="badge bg-primary me-1 mb-1">{{ $permission->name }}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">No permissions assigned</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="card mt-4 border-danger">
                        <div class="card-body">
                            <h6 class="card-title text-danger">
                                <i class="bx bx-error me-1"></i>Danger Zone
                            </h6>
                            <p class="text-muted">
                                Deleting this role will remove it permanently. Users assigned to this role will lose these permissions.
                            </p>
                            <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this role? This action cannot be undone.')">
                                    <i class="bx bxs-trash me-1"></i>Delete Role
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--end page wrapper -->

    <!-- JavaScript for Select All functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select_all');
            const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');

            // Update select all checkbox initial state
            function updateSelectAllState() {
                const checkedCount = document.querySelectorAll('.permission-checkbox:checked').length;
                const totalCount = permissionCheckboxes.length;

                if (checkedCount === 0) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                } else if (checkedCount === totalCount) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.indeterminate = false;
                } else {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = true;
                }
            }

            // Initial state
            updateSelectAllState();

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    permissionCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });

                // Update select all checkbox state when individual checkboxes change
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        updateSelectAllState();
                    });
                });
            }
        });
    </script>

@endsection
