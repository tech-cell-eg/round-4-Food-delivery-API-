@extends("dashboard.layouts.master")

@section("title", "Edit Profile")

@section("content")
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Profile</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bx bx-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bx bx-error me-2"></i>
                <strong>Error!</strong> Please correct the following errors:
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="container">
            <div class="main-body">
                <div class="row">
                    <!-- Profile Information -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center text-center">
                                    <div class="position-relative">
                                        <img src="{{ auth()->guard('admin')->user()->profile_image ? asset('storage/' . auth()->guard('admin')->user()->profile_image) : asset('assets/images/avatars/avatar-2.png') }}"
                                             alt="Admin Avatar" class="rounded-circle p-1 bg-primary" width="110" height="110" style="object-fit: cover;">
                                        <div class="position-absolute bottom-0 end-0">
                                            <label for="profile_image" class="btn btn-primary btn-sm rounded-circle" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bx bx-camera"></i>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <h4>{{ auth()->guard('admin')->user()->name ?? 'Not Set' }}</h4>
                                        <p class="text-secondary mb-1">System Administrator - Food Delivery</p>
                                        <p class="text-muted font-size-sm">{{ auth()->guard('admin')->user()->email ?? 'Not Set' }}</p>
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                </div>
                                <hr class="my-4" />
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <h6 class="mb-0">
                                            <i class="bx bx-phone me-2 text-primary"></i>Phone Number
                                        </h6>
                                        <span class="text-secondary">{{ auth()->guard('admin')->user()->phone ?? 'Not Set' }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <h6 class="mb-0">
                                            <i class="bx bx-user-check me-2 text-success"></i>User Type
                                        </h6>
                                        <span class="text-secondary">{{ auth()->guard('admin')->user()->type ?? 'Administrator' }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <h6 class="mb-0">
                                            <i class="bx bx-time me-2 text-info"></i>Last Login
                                        </h6>
                                        <span class="text-secondary">{{ auth()->guard('admin')->user()->admin?->last_login_at ? auth()->guard('admin')->user()->admin->last_login_at->diffForHumans() : 'Not Set' }}</span>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Information -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="bx bx-edit me-2"></i>Edit Personal Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <!-- Profile Image Upload -->
                                    <input type="file" id="profile_image" name="profile_image" accept="image/*" style="display: none;" onchange="previewImage(this)">

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Full Name <span class="text-danger">*</span></h6>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                   name="name" value="{{ old('name', auth()->guard('admin')->user()->name) }}"
                                                   placeholder="Enter full name" required />
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Email Address <span class="text-danger">*</span></h6>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                   name="email" value="{{ old('email', auth()->guard('admin')->user()->email) }}"
                                                   placeholder="Enter email address" required />
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Phone Number</h6>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                   name="phone" value="{{ old('phone', auth()->guard('admin')->user()->phone) }}"
                                                   placeholder="Enter phone number" />
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Bio</h6>
                                        </div>
                                        <div class="col-sm-9">
                                            <textarea class="form-control @error('bio') is-invalid @enderror"
                                                      name="bio" rows="3" placeholder="Enter a brief description about yourself">{{ old('bio', auth()->guard('admin')->user()->bio) }}</textarea>
                                            @error('bio')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <hr class="my-4">
                                    <h6 class="text-primary mb-3">
                                        <i class="bx bx-lock me-2"></i>Change Password (Optional)
                                    </h6>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Current Password</h6>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                                   name="current_password" placeholder="Enter current password" />
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">New Password</h6>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                   name="password" placeholder="Enter new password" />
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Confirm Password</h6>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control"
                                                   name="password_confirmation" placeholder="Confirm new password" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-9">
                                            <button type="submit" class="btn btn-primary px-4">
                                                <i class="bx bx-save me-2"></i>Save Changes
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('img[alt="Admin Avatar"]').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}


@endsection
