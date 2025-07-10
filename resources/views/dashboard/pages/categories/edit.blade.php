@extends('dashboard.layouts.master')

@section("title", "Edit Category")

@section("content")

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">

            <!-- breadcrumb -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Categories</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Category - ({{ $category->name }})</li>
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
                                <div><i class="bx bx-category me-1 font-22 text-primary"></i></div>
                                <h5 class="mb-0 text-primary">Edit Category</h5>
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

                            <!-- Category Edit Form -->
                            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="row g-3">
                                @csrf
                                @method("PATCH")

                                <div class="col-12">
                                    <label for="name" class="form-label">Category Name *</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $category->name) }}"
                                           placeholder="Enter category name (e.g., Pizza, Burgers, Desserts)"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="meal_type" class="form-label">Meal Type *</label>
                                    <select class="form-select @error('meal_type') is-invalid @enderror" 
                                            id="meal_type" 
                                            name="meal_type" 
                                            required>
                                        <option value="">Select meal type</option>
                                        <option value="breakfast" {{ old('meal_type', $category->meal_type) == 'breakfast' ? 'selected' : '' }}>Breakfast</option>
                                        <option value="lunch" {{ old('meal_type', $category->meal_type) == 'lunch' ? 'selected' : '' }}>Lunch</option>
                                        <option value="dinner" {{ old('meal_type', $category->meal_type) == 'dinner' ? 'selected' : '' }}>Dinner</option>
                                    </select>
                                    @error('meal_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Current Image Display -->
                                @if($category->image)
                                <div class="col-12">
                                    <label class="form-label">Current Image:</label>
                                    <br>
                                    @if(\Storage::disk('public')->exists($category->image))
                                        <img id="currentImg" src="{{ asset('storage/' . $category->image) }}" 
                                             alt="Current Category Image" 
                                             style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px; border: 2px solid #dee2e6;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center"
                                             style="width: 120px; height: 120px; background-color: #f8f9fa; border-radius: 8px; border: 2px solid #dee2e6;">
                                            <i class="bx bx-image" style="font-size: 32px; color: #6c757d;"></i>
                                        </div>
                                    @endif
                                </div>
                                @endif

                                <div class="col-12">
                                    <label for="image" class="form-label">New Category Image (optional)</label>
                                    <input type="file"
                                           class="form-control @error('image') is-invalid @enderror"
                                           id="image"
                                           name="image"
                                           accept="image/*"
                                           onchange="previewImage(this)">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Leave empty to keep current image. Accepted formats: JPG, JPEG, PNG, GIF. Max size: 2MB</small>
                                    
                                    <!-- New Image Preview -->
                                    <div id="imagePreview" class="mt-3" style="display: none;">
                                        <label class="form-label">New Image Preview:</label>
                                        <br>
                                        <img id="previewImg" src="" alt="Preview" 
                                             style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px; border: 2px solid #28a745;">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <hr>
                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-primary px-5">
                                            <i class="bx bxs-save me-1"></i>Update Category
                                        </button>
                                        <a href="{{ route('admin.categories.index') }}" class="btn btn-light px-5">
                                            <i class="bx bx-x me-1"></i>Cancel
                                        </a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                    <!-- Category Examples Card -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h6 class="card-title text-info">
                                <i class="bx bx-info-circle me-1"></i>Category Examples by Meal Type
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Breakfast:</strong>
                                    <ul class="list-unstyled ms-3">
                                        <li><code>Traditional Breakfast</code></li>
                                        <li><code>Healthy Breakfast</code></li>
                                        <li><code>Pastries & Sweets</code></li>
                                        <li><code>Continental Breakfast</code></li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <strong>Lunch:</strong>
                                    <ul class="list-unstyled ms-3">
                                        <li><code>Egyptian Cuisine</code></li>
                                        <li><code>Italian Cuisine</code></li>
                                        <li><code>Grilled & BBQ</code></li>
                                        <li><code>Sandwiches & Wraps</code></li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <strong>Dinner:</strong>
                                    <ul class="list-unstyled ms-3">
                                        <li><code>Fine Dining</code></li>
                                        <li><code>Traditional Egyptian</code></li>
                                        <li><code>International Cuisine</code></li>
                                        <li><code>Comfort Food</code></li>
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

    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
            }
        }
    </script>

@endsection
