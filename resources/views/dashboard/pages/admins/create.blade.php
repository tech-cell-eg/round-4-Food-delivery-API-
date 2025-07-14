@extends('dashboard.layouts.master')

@section("title", "Create New Admin")

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
                            <li class="breadcrumb-item active" aria-current="page">Create New Admin</li>
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
                            <h5 class="card-title text-primary mb-0">
                                <i class="bx bxs-user-plus me-2"></i>Create New Admin
                            </h5>
                            <p class="text-muted mb-0">Fill in the required information to create a new admin account</p>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.admins.store') }}" method="POST" class="row g-3" enctype="multipart/form-data">
                                @csrf

                                <!-- Personal Information Section -->
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">
                                        <i class="bx bxs-user me-1"></i>Personal Information
                                    </h6>
                                </div>

                                <!-- Profile Image -->
                                <div class="col-12">
                                    <label for="profile_image" class="form-label">Profile Image</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="profile-preview">
                                            <img id="profilePreview"
                                                 src="{{ asset('assets/images/avatars/avatar-1.png') }}"
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
                                            <small class="text-muted">Optional. Supported formats: JPG, PNG, GIF. Max size: 2MB</small>
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
                                           value="{{ old('name') }}"
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
                                           value="{{ old('email') }}"
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
                                           value="{{ old('phone') }}"
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
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                                              placeholder="Enter a brief description about the admin">{{ old('bio') }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Optional. Brief description of admin's experience or responsibilities</small>
                                </div>

                                <!-- Account Security Section -->
                                <div class="col-12">
                                    <hr>
                                    <h6 class="text-primary mb-3">
                                        <i class="bx bxs-lock me-1"></i>Account Security
                                    </h6>
                                </div>

                                <!-- Password -->
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password *</label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               id="password"
                                               name="password"
                                               placeholder="Enter password"
                                               required>
                                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                            <i class="bx bx-hide" id="passwordIcon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Must be at least 8 characters long</small>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           placeholder="Re-enter password"
                                           required>
                                </div>

                                <!-- Roles & Permissions Section -->
                                <div class="col-12">
                                    <hr>
                                    <h6 class="text-primary mb-3">
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

                                            @foreach($roles as $role)
                                                <div class="col-md-6 col-lg-4 mb-3">
                                                    <div class="card border">
                                                        <div class="card-body p-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input role-checkbox"
                                                                       type="checkbox"
                                                                       name="roles[]"
                                                                       value="{{ $role->name }}"
                                                                       id="role_{{ $role->id }}"
                                                                       {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bold" for="role_{{ $role->id }}">
                                                                    <i class="bx bxs-user-badge me-1 text-primary"></i>{{ $role->name }}
                                                                </label>
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
                                        <button type="submit" class="btn btn-primary px-5">
                                            <i class="bx bxs-save me-1"></i>Create Admin
                                        </button>
                                        <a href="{{ route('admin.admins.index') }}" class="btn btn-light px-5">
                                            <i class="bx bx-x me-1"></i>Cancel
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Help Card -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h6 class="card-title text-info">
                                <i class="bx bx-help-circle me-1"></i>Important Tips
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Account Information:</strong>
                                    <ul class="list-unstyled ms-3 small">
                                        <li><i class="bx bx-check text-success me-1"></i>Ensure email address is correct</li>
                                        <li><i class="bx bx-check text-success me-1"></i>Use a strong password</li>
                                        <li><i class="bx bx-check text-success me-1"></i>Fill out all personal information</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <strong>Roles & Permissions:</strong>
                                    <ul class="list-unstyled ms-3 small">
                                        <li><i class="bx bx-check text-warning me-1"></i>Choose roles carefully</li>
                                        <li><i class="bx bx-check text-warning me-1"></i>Roles can be modified later</li>
                                        <li><i class="bx bx-check text-warning me-1"></i>Roles determine permissions</li>
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
                });
            }
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
            color: var(--bs-primary);
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        @media (max-width: 768px) {
            .breadcrumb-title {
                font-size: 0.9rem;
            }

            .card-header h5 {
                font-size: 1.1rem;
            }
        }
    </style>

@endsection
