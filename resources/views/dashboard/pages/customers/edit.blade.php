@extends('dashboard.layouts.master')

@section("title", "Edit Customer")

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
                            <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Customers</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Customer</li>
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
                                <i class="bx bxs-edit me-2"></i>Edit Customer: {{ $customer->user->name ?? 'Not specified' }}
                            </h5>
                            <p class="text-muted mb-0">Modify the required information for the customer account</p>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST" class="row g-3" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

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
                                                 src="{{ asset('storage/' . $customer->user->profile_image) ?? asset('assets/images/avatars/avatar-1.png') }}"
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
                                           value="{{ old('name', $customer->user->name ?? '') }}"
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
                                           value="{{ old('email', $customer->user->email ?? '') }}"
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
                                           value="{{ old('phone', $customer->user->phone ?? '') }}"
                                           placeholder="Enter phone number">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Account Status *</label>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                            id="status"
                                            name="status"
                                            required>
                                        <option value="">Select Status</option>
                                        <option value="active" {{ (old('status', $customer->status ?? '') == 'active') ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ (old('status', $customer->status ?? '') == 'inactive') ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Bio -->
                                <div class="col-12">
                                    <label for="bio" class="form-label">About Customer</label>
                                    <textarea class="form-control @error('bio') is-invalid @enderror"
                                              id="bio"
                                              name="bio"
                                              rows="3"
                                              placeholder="Enter a brief description about the customer">{{ old('bio', $customer->user->bio ?? '') }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Optional. Brief description or notes about the customer</small>
                                </div>

                                <!-- Account Security Section -->
                                <div class="col-12">
                                    <hr>
                                    <h6 class="text-primary mb-3">
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

                                <!-- Email Verification Section -->
                                <div class="col-12">
                                    <hr>
                                    <h6 class="text-primary mb-3">
                                        <i class="bx bxs-check-shield me-1"></i>Account Verification
                                    </h6>
                                </div>

                                <!-- Email Verification Status -->
                                <div class="col-12">
                                    <div class="alert {{ $customer->user->email_verified_at ? 'alert-success' : 'alert-warning' }}">
                                        <i class="bx {{ $customer->user->email_verified_at ? 'bx-check-circle' : 'bx-error-circle' }} me-1"></i>
                                        Current Status:
                                        <strong>{{ $customer->user->email_verified_at ? 'Email Verified' : 'Email Not Verified' }}</strong>
                                        @if($customer->user->email_verified_at)
                                            <br><small>Verified on: {{ $customer->user->email_verified_at->format('M d, Y H:i') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <!-- Email Verified Checkbox -->
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="email_verified"
                                               id="email_verified"
                                               value="1"
                                               {{ ($customer->user->email_verified_at || old('email_verified')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email_verified">
                                            <i class="bx bx-check-circle me-1 text-success"></i>Mark email as verified
                                        </label>
                                    </div>
                                    <small class="text-muted">Check this to verify the customer's email address manually</small>
                                </div>

                                <!-- Form Actions -->
                                <div class="col-12">
                                    <hr>
                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-primary px-5">
                                            <i class="bx bxs-save me-1"></i>Update Customer
                                        </button>
                                        <a href="{{ route('admin.customers.index') }}" class="btn btn-light px-5">
                                            <i class="bx bx-x me-1"></i>Cancel
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Customer Information Card -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h6 class="card-title text-info">
                                <i class="bx bx-info-circle me-1"></i>Current Customer Information
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Basic Information:</strong>
                                    <ul class="list-unstyled ms-3 small">
                                        <li><i class="bx bx-user text-primary me-1"></i>ID: {{ $customer->id }}</li>
                                        <li><i class="bx bx-envelope text-primary me-1"></i>Email: {{ $customer->user->email ?? 'Not specified' }}</li>
                                        <li><i class="bx bx-phone text-primary me-1"></i>Phone: {{ $customer->user->phone ?? 'Not specified' }}</li>
                                        <li><i class="bx bx-time text-primary me-1"></i>Last Active: {{ $customer->last_login_at ? $customer->last_login_at->diffForHumans() : 'Never logged in' }}</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <strong>Account Status:</strong>
                                    <ul class="list-unstyled ms-3 small">
                                        <li>
                                            <i class="bx bx-circle text-{{ $customer->status === 'active' ? 'success' : 'warning' }} me-1"></i>
                                            Status: {{ $customer->status === 'active' ? 'Active' : 'Inactive' }}
                                        </li>
                                        <li>
                                            <i class="bx bx-envelope text-{{ $customer->user->email_verified_at ? 'success' : 'warning' }} me-1"></i>
                                            Email: {{ $customer->user->email_verified_at ? 'Verified' : 'Not Verified' }}
                                        </li>
                                        <li><i class="bx bx-calendar text-primary me-1"></i>Joined: {{ $customer->created_at ? $customer->created_at->format('Y-m-d') : 'Not specified' }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Help Card -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h6 class="card-title text-primary">
                                <i class="bx bx-help-circle me-1"></i>Customer Editing Guidelines
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Account Information:</strong>
                                    <ul class="list-unstyled ms-3 small">
                                        <li><i class="bx bx-check text-success me-1"></i>Ensure email address is valid</li>
                                        <li><i class="bx bx-check text-success me-1"></i>Password change is optional</li>
                                        <li><i class="bx bx-check text-success me-1"></i>Profile image can be updated</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <strong>Account Status:</strong>
                                    <ul class="list-unstyled ms-3 small">
                                        <li><i class="bx bx-check text-info me-1"></i>Active customers can place orders</li>
                                        <li><i class="bx bx-check text-warning me-1"></i>Inactive customers are restricted</li>
                                        <li><i class="bx bx-check text-primary me-1"></i>Email verification affects features</li>
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
        });
    </script>

    <!-- Custom CSS -->
    <style>
        .profile-preview img {
            object-fit: cover;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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

            .d-flex.gap-3 {
                flex-direction: column;
            }

            .d-flex.gap-3 .btn {
                margin-bottom: 0.5rem;
            }
        }
    </style>

@endsection
