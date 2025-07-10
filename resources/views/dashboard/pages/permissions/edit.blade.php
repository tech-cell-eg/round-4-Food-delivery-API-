@extends('dashboard.layouts.master')

@section("title", "Edit Permission")

@section("content")

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">

            <!-- breadcrumb -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Permissions</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Permissions</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Permission - ({{ $permission->name }})</li>
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
                                <div><i class="bx bxs-shield me-1 font-22 text-primary"></i></div>
                                <h5 class="mb-0 text-primary">Edit Permission</h5>
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


                            <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST" class="row g-3">
                                @csrf
                                @method("PATCH")

                                <div class="col-12">
                                    <label for="name" class="form-label">Permission Name *</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ $permission->name }}"
                                           placeholder="Enter permission name (e.g., create-users, edit-posts)"
                                           required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Use lowercase letters, numbers, and hyphens only. Example: create-users, edit-posts, delete-comments</small>
                                </div>

                                <div class="col-12">
                                    <hr>
                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-primary px-5">
                                            <i class="bx bxs-save me-1"></i>Edit Permission
                                        </button>
                                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-light px-5">
                                            <i class="bx bx-x me-1"></i>Cancel
                                        </a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                    <!-- Permission Examples Card -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h6 class="card-title text-info">
                                <i class="bx bx-info-circle me-1"></i>Permission Naming Examples
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>User Management:</strong>
                                    <ul class="list-unstyled ms-3">
                                        <li><code>create-users</code> - Create new users</li>
                                        <li><code>edit-users</code> - Edit user information</li>
                                        <li><code>delete-users</code> - Delete users</li>
                                        <li><code>view-users</code> - View user list</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <strong>Content Management:</strong>
                                    <ul class="list-unstyled ms-3">
                                        <li><code>create-posts</code> - Create new posts</li>
                                        <li><code>edit-posts</code> - Edit posts</li>
                                        <li><code>publish-posts</code> - Publish posts</li>
                                        <li><code>moderate-comments</code> - Moderate comments</li>
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

@endsection
