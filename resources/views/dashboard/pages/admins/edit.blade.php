@extends('dashboard.layouts.master')

@section("title", "Edit Admin")

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
                            <li class="breadcrumb-item"><a href="{{ route('admin.admins.index') }}">Admins</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Admin</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end breadcrumb -->

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <!-- Main Form Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title text-warning mb-0">
                                <i class="bx bxs-edit me-2"></i>Edit Admin: {{ $admin->user->name ?? 'Not specified' }}
                            </h5>
                            <p class="text-muted mb-0">Modify the required information for the admin account</p>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST" class="row g-3" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Personal Information Section -->
                                <div class="col-12">
                                    <h6 class="text-warning mb-3">
                                        <i class="bx bxs-user me-1"></i>Personal Information
                                    </h6>
                                </div>

                                <!-- Profile Image -->
                                <div class="col-12">
                                    <label for="profile_image" class="form-label">Profile Image</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="profile-preview">
                                            <img id="profilePreview"
                                                 src="{{ asset('storage/' . $admin->user->profile_image) ?? asset('assets/images/avatars/avatar-1.png') }}"
                                                 alt="Profile Preview"
                                                 class="rounded-circle border"
                                                 width="80" height="80">
                                        </div>
                                        <div class="flex-grow-1">
                                            <input type="file"
                                                   class="form-control @error('profile_image') is-invalid @enderror"
                                                   id="profile_image"
                                                   name="profile_image"
                                                   accept="image/*">
                                            @error('profile_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Optional. Leave empty to keep current image. Supported formats: JPG, PNG, GIF. Max size: 2MB</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Name -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $admin->user->name ?? '') }}"
                                           placeholder="Enter full name"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', $admin->user->email ?? '') }}"
                                           placeholder="Enter email address"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone', $admin->user->phone ?? '') }}"
                                           placeholder="Enter phone number">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                            id="status"
                                            name="status"
                                            required>
                                        <option value="">Select Status</option>
                                        <option value="active" {{ (old('status', $admin->status ?? '') == 'active') ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ (old('status', $admin->status ?? '') == 'inactive') ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Bio -->
                                <div class="col-12">
                                    <label for="bio" class="form-label">Biography</label>
                                    <textarea class="form-control @error('bio') is-invalid @enderror"
                                              id="bio"
                                              name="bio"
                                              rows="3"
                                              placeholder="Enter a brief description about the admin">{{ old('bio', $admin->user->bio ?? '') }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Optional. Brief description of admin's experience or responsibilities</small>
                                </div>

                                <!-- Account Security Section -->
                                <div class="col-12">
                                    <hr>
                                    <h6 class="text-warning mb-3">
                                        <i class="bx bxs-lock me-1"></i>Account Security
                                    </h6>
                                    <div class="alert alert-info">
                                        <i class="bx bx-info-circle me-1"></i>
                                        Leave both password fields empty to keep the current password
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="col-md-6">
                                    <label for="password" class="form-label">New Password</label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               id="password"
                                               name="password"
                                               placeholder="Enter new password">
                                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                            <i class="bx bx-hide" id="passwordIcon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Optional. Must be at least 8 characters long if changed</small>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           placeholder="Re-enter new password">
                                </div>

                                <!-- Current Roles Display -->
                                @if($admin->roles && $admin->roles->count() > 0)
                                    <div class="col-12">
                                        <div class="alert alert-light">
                                            <h6 class="text-info mb-2">
                                                <i class="bx bxs-user-badge me-1"></i>Current Roles:
                                            </h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($admin->roles as $role)
                                                    <span class="badge bg-info text-white">
                                                        <i class="bx bxs-shield-alt-2 me-1"></i>{{ $role->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Roles & Permissions Section -->
                                <div class="col-12">
                                    <hr>
                                    <h6 class="text-warning mb-3">
                                        <i class="bx bxs-shield me-1"></i>Roles & Permissions
                                    </h6>

                                    @if(isset($roles) && count($roles) > 0)
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <div class="alert alert-info">
                                                    <i class="bx bx-info-circle me-1"></i>
                                                    Select appropriate roles for the admin. They will receive all permissions associated with these roles.
                                                </div>
                                            </div>

                                            @php
                                                $currentRoles = old('roles', $admin->roles->pluck('name')->toArray() ?? []);
                                            @endphp

                                            @foreach($roles as $role)
                                                <div class="col-md-6 col-lg-4 mb-3">
                                                    <div class="card border {{ in_array($role->name, $currentRoles) ? 'border-warning bg-light' : '' }}">
                                                        <div class="card-body p-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input role-checkbox"
                                                                       type="checkbox"
                                                                       name="roles[]"
                                                                       value="{{ $role->name }}"
                                                                       id="role_{{ $role->id }}"
                                                                       {{ in_array($role->name, $currentRoles) ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bold" for="role_{{ $role->id }}">
                                                                    <i class="bx bxs-user-badge me-1 text-warning"></i>{{ $role->name }}
                                                                </label>
                                                                @if(in_array($role->name, $currentRoles))
                                                                    <span class="badge bg-warning text-dark ms-2">Currently Assigned</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="bx bx-shield-x me-1"></i>
                                            No roles available. <a href="{{ route('admin.roles.create') }}">Create roles first</a>.
                                        </div>
                                    @endif

                                    @error('roles')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Form Actions -->
                                <div class="col-12">
                                    <hr>
                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-warning px-5">
                                            <i class="bx bxs-save me-1"></i>Update Admin
                                        </button>
                                        <a href="{{ route('admin.admins.index') }}" class="btn btn-light px-5">
                                            <i class="bx bx-x me-1"></i>Cancel
                                        </a>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Admin Information Card -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h6 class="card-title text-info">
                                <i class="bx bx-info-circle me-1"></i>Current Admin Information
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Basic Information:</strong>
                                    <ul class="list-unstyled ms-3 small">
                                        <li><i class="bx bx-user text-primary me-1"></i>ID: {{ $admin->id }}</li>
                                        <li><i class="bx bx-envelope text-primary me-1"></i>Email: {{ $admin->user->email ?? 'Not specified' }}</li>
                                        <li><i class="bx bx-phone text-primary me-1"></i>Phone: {{ $admin->user->phone ?? 'Not specified' }}</li>
                                        <li><i class="bx bx-time text-primary me-1"></i>Last Login: {{ $admin->last_login_at ? $admin->last_login_at->diffForHumans() : 'Never logged in' }}</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <strong>Status & Roles:</strong>
                                    <ul class="list-unstyled ms-3 small">
                                        <li>
                                            <i class="bx bx-circle text-{{ $admin->status === 'active' ? 'success' : 'warning' }} me-1"></i>
                                            Status: {{ $admin->status === 'active' ? 'Active' : 'Inactive' }}
                                        </li>
                                        <li><i class="bx bx-shield text-primary me-1"></i>Roles: {{ $admin->roles->count() ?? 0 }}</li>
                                        <li><i class="bx bx-lock text-primary me-1"></i>Permissions: {{ $admin->getAllPermissions('admin')->count() ?? 0 }}</li>
                                        <li><i class="bx bx-calendar text-primary me-1"></i>Created: {{ $admin->created_at ? $admin->created_at->format('Y-m-d') : 'Not specified' }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Help Card -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h6 class="card-title text-warning">
                                <i class="bx bx-help-circle me-1"></i>Important Tips for Editing
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Editing Information:</strong>
                                    <ul class="list-unstyled ms-3 small">
                                        <li><i class="bx bx-check text-success me-1"></i>Ensure email address is correct</li>
                                        <li><i class="bx bx-check text-success me-1"></i>Password is optional</li>
                                        <li><i class="bx bx-check text-success me-1"></i>Profile image can be changed</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <strong>Roles & Permissions:</strong>
                                    <ul class="list-unstyled ms-3 small">
                                        <li><i class="bx bx-check text-warning me-1"></i>Review selected roles</li>
                                        <li><i class="bx bx-check text-warning me-1"></i>Changes take effect immediately</li>
                                        <li><i class="bx bx-check text-warning me-1"></i>Removing roles affects permissions</li>
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

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Profile Image Preview
            const profileImageInput = document.getElementById('profile_image');
            const profilePreview = document.getElementById('profilePreview');

            if (profileImageInput) {
                profileImageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            profilePreview.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Password Toggle
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');

            if (togglePassword) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    passwordIcon.classList.toggle('bx-hide');
                    passwordIcon.classList.toggle('bx-show');
                });
            }

            // Form Validation
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('password_confirmation').value;

                    // Only validate password if it's provided
                    if (password.length > 0) {
                        if (password !== confirmPassword) {
                            e.preventDefault();
                            alert('Password and confirm password do not match');
                            return false;
                        }

                        if (password.length < 8) {
                            e.preventDefault();
                            alert('Password must be at least 8 characters long');
                            return false;
                        }
                    }
                });
            }

            // Highlight role changes
            const roleCheckboxes = document.querySelectorAll('.role-checkbox');
            roleCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const card = this.closest('.card');
                    if (this.checked) {
                        card.classList.add('border-warning', 'bg-light');
                    } else {
                        card.classList.remove('border-warning', 'bg-light');
                    }
                });
            });
        });
    </script>

    <!-- Custom CSS -->
    <style>
        .profile-preview img {
            object-fit: cover;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .permissions-preview .badge {
            font-size: 0.7rem;
        }

        .role-checkbox:checked + label {
            color: var(--bs-warning);
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--bs-warning);
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }

        .btn-warning {
            background-color: var(--bs-warning);
            border-color: var(--bs-warning);
            color: var(--bs-dark);
        }

        .btn-warning:hover {
            background-color: #ffca2c;
            border-color: #ffc720;
            color: var(--bs-dark);
        }

        .border-warning.bg-light {
            background-color: rgba(255, 193, 7, 0.1) !important;
        }

        @media (max-width: 768px) {
            .breadcrumb-title {
                font-size: 0.9rem;
            }

            .card-header h5 {
                font-size: 1.1rem;
            }

            .d-flex.gap-3 {
                flex-direction: column;
            }

            .d-flex.gap-3 .btn {
                margin-bottom: 0.5rem;
            }
        }
    </style>

@endsection
