@extends('dashboard.layouts.master')

@section("title", "Create Role")

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
                            <li class="breadcrumb-item active" aria-current="page">Create Role</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end breadcrumb -->

            <div class="row">
                <div class="col-xl-9 mx-auto">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <div><i class="bx bxs-user-badge me-1 font-22 text-primary"></i></div>
                                <h5 class="mb-0 text-primary">Create New Role</h5>
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

                            <!-- Role Creation Form -->
                            <form action="{{ route('admin.roles.store') }}" method="POST" class="row g-3">
                                @csrf

                                <div class="col-12">
                                    <label for="name" class="form-label">Role Name *</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
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
                                    <h6 class="text-primary mb-3">
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
                                                               {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
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
                                        <button type="submit" class="btn btn-primary px-5">
                                            <i class="bx bxs-save me-1"></i>Create Role
                                        </button>
                                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light px-5">
                                            <i class="bx bx-x me-1"></i>Cancel
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Role Examples Card -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h6 class="card-title text-info">
                                <i class="bx bx-info-circle me-1"></i>Common Role Examples
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Administrative Roles:</strong>
                                    <ul class="list-unstyled ms-3">
                                        <li><strong>Super Admin</strong> - Full system access</li>
                                        <li><strong>Admin</strong> - Administrative privileges</li>
                                        <li><strong>Manager</strong> - Team management</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <strong>Operational Roles:</strong>
                                    <ul class="list-unstyled ms-3">
                                        <li><strong>Editor</strong> - Content management</li>
                                        <li><strong>Moderator</strong> - Content moderation</li>
                                        <li><strong>Customer Support</strong> - User assistance</li>
                                    </ul>
                                </div>
                            </div>
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

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    permissionCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });

                // Update select all checkbox state when individual checkboxes change
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const checkedCount = document.querySelectorAll('.permission-checkbox:checked').length;
                        selectAllCheckbox.checked = checkedCount === permissionCheckboxes.length;
                        selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < permissionCheckboxes.length;
                    });
                });
            }
        });
    </script>

@endsection
