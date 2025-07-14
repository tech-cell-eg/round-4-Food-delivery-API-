@extends('dashboard.layouts.master')

@section("title", "Create Dish")

@section("content")

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">

            <!-- breadcrumb -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Dishes</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.dishes.index') }}">Dishes</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create Dish</li>
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
                                <div><i class="bx bx-dish me-1 font-22 text-primary"></i></div>
                                <h5 class="mb-0 text-primary">Create New Dish</h5>
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

                            <!-- Dish Creation Form -->
                            <form action="{{ route('admin.dishes.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                                @csrf

                                <div class="col-md-6">
                                    <label for="name" class="form-label">Dish Name *</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           placeholder="Enter dish name (e.g., Grilled Chicken, Pasta Bolognese)"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="chef_id" class="form-label">Chef *</label>
                                    <select class="form-select @error('chef_id') is-invalid @enderror" 
                                            id="chef_id" 
                                            name="chef_id" 
                                            required>
                                        <option value="">Select a chef</option>
                                        @foreach($chefs as $chef)
                                            <option value="{{ $chef->id }}" {{ old('chef_id') == $chef->id ? 'selected' : '' }}>
                                                {{ $chef->user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('chef_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                            id="category_id" 
                                            name="category_id">
                                        <option value="">Select a category (optional)</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }} ({{ ucfirst($category->meal_type) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="is_available" class="form-label">Availability Status</label>
                                    <select class="form-select @error('is_available') is-invalid @enderror" 
                                            id="is_available" 
                                            name="is_available">
                                        <option value="1" {{ old('is_available', '1') == '1' ? 'selected' : '' }}>Available</option>
                                        <option value="0" {{ old('is_available') == '0' ? 'selected' : '' }}>Not Available</option>
                                    </select>
                                    @error('is_available')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description"
                                              name="description"
                                              rows="4"
                                              placeholder="Enter dish description, ingredients, special notes...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="image" class="form-label">Dish Image</label>
                                    <input type="file"
                                           class="form-control @error('image') is-invalid @enderror"
                                           id="image"
                                           name="image"
                                           accept="image/*"
                                           onchange="previewImage(this)">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Accepted formats: JPG, JPEG, PNG, GIF. Max size: 2MB</small>
                                    
                                    <!-- Image Preview -->
                                    <div id="imagePreview" class="mt-3" style="display: none;">
                                        <label class="form-label">Image Preview:</label>
                                        <br>
                                        <img id="previewImg" src="" alt="Preview" 
                                             style="width: 200px; height: 150px; object-fit: cover; border-radius: 8px; border: 2px solid #dee2e6;">
                                    </div>
                                </div>

                                <!-- Dish Sizes & Prices -->
                                <div class="col-12">
                                    <label class="form-label">Dish Sizes & Prices *</label>
                                    <div class="border rounded p-3">
                                        <div id="sizesContainer">
                                            <div class="size-row mb-3">
                                                <div class="row align-items-end">
                                                    <div class="col-md-4">
                                                        <label class="form-label small">Size</label>
                                                        <select class="form-select" name="sizes[0][size]" required>
                                                            <option value="">Select size</option>
                                                            <option value="small">Small</option>
                                                            <option value="medium">Medium</option>
                                                            <option value="large">Large</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label small">Price ($)</label>
                                                        <input type="number" class="form-control" name="sizes[0][price]" 
                                                               placeholder="0.00" step="0.01" min="0" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button type="button" class="btn btn-outline-danger btn-sm remove-size" disabled>
                                                            <i class="bx bx-trash"></i> Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="addSize">
                                            <i class="bx bx-plus"></i> Add Another Size
                                        </button>
                                    </div>
                                    <small class="text-muted">At least one size is required. Each size must have a price.</small>
                                    @error('sizes')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Ingredients Selection -->
                                <div class="col-12">
                                    <label class="form-label">Ingredients (Optional)</label>
                                    <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                        @foreach($ingredients as $ingredient)
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       value="{{ $ingredient->id }}" 
                                                       id="ingredient_{{ $ingredient->id }}"
                                                       name="ingredients[]"
                                                       {{ in_array($ingredient->id, old('ingredients', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="ingredient_{{ $ingredient->id }}">
                                                    <span class="badge bg-light text-dark border me-1">{{ ucfirst($ingredient->type) }}</span>
                                                    {{ $ingredient->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <small class="text-muted">Select ingredients that are used in this dish</small>
                                </div>

                                <div class="col-12">
                                    <hr>
                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-primary px-5">
                                            <i class="bx bxs-save me-1"></i>Create Dish
                                        </button>
                                        <a href="{{ route('admin.dishes.index') }}" class="btn btn-light px-5">
                                            <i class="bx bx-x me-1"></i>Cancel
                                        </a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                    <!-- Dish Creation Tips Card -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h6 class="card-title text-info">
                                <i class="bx bx-info-circle me-1"></i>Dish Creation Tips
                            </h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Name Guidelines:</strong>
                                    <ul class="list-unstyled ms-3">
                                        <li><code>Use descriptive names</code></li>
                                        <li><code>Include cooking method</code></li>
                                        <li><code>Mention main ingredients</code></li>
                                        <li><code>Keep it under 50 characters</code></li>
                                    </ul>
                                </div>
                                <div class="col-md-3">
                                    <strong>Description Best Practices:</strong>
                                    <ul class="list-unstyled ms-3">
                                        <li><code>Describe taste and texture</code></li>
                                        <li><code>Mention special ingredients</code></li>
                                        <li><code>Include allergen information</code></li>
                                        <li><code>Add preparation notes</code></li>
                                    </ul>
                                </div>
                                <div class="col-md-3">
                                    <strong>Sizes & Pricing:</strong>
                                    <ul class="list-unstyled ms-3">
                                        <li><code>At least one size required</code></li>
                                        <li><code>Small: Starter portions</code></li>
                                        <li><code>Medium: Regular portions</code></li>
                                        <li><code>Large: Sharing portions</code></li>
                                        <li><code>Price logically by size</code></li>
                                    </ul>
                                </div>
                                <div class="col-md-3">
                                    <strong>Image Tips:</strong>
                                    <ul class="list-unstyled ms-3">
                                        <li><code>Use high-quality photos</code></li>
                                        <li><code>Good lighting is essential</code></li>
                                        <li><code>Show the complete dish</code></li>
                                        <li><code>Square format works best</code></li>
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

        // Manage dish sizes
        let sizeIndex = 1;
        const maxSizes = 3; // Limit to 3 sizes (small, medium, large)

        document.getElementById('addSize').addEventListener('click', function() {
            if (document.querySelectorAll('.size-row').length >= maxSizes) {
                alert('Maximum 3 sizes allowed');
                return;
            }

            const sizesContainer = document.getElementById('sizesContainer');
            const newSizeRow = document.createElement('div');
            newSizeRow.className = 'size-row mb-3';
            newSizeRow.innerHTML = `
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small">Size</label>
                        <select class="form-select" name="sizes[${sizeIndex}][size]" required>
                            <option value="">Select size</option>
                            <option value="small">Small</option>
                            <option value="medium">Medium</option>
                            <option value="large">Large</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Price ($)</label>
                        <input type="number" class="form-control" name="sizes[${sizeIndex}][price]" 
                               placeholder="0.00" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-size">
                            <i class="bx bx-trash"></i> Remove
                        </button>
                    </div>
                </div>
            `;
            
            sizesContainer.appendChild(newSizeRow);
            sizeIndex++;
            updateRemoveButtons();
        });

        // Handle remove size
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-size') || e.target.closest('.remove-size')) {
                const sizeRow = e.target.closest('.size-row');
                sizeRow.remove();
                updateRemoveButtons();
            }
        });

        function updateRemoveButtons() {
            const removeButtons = document.querySelectorAll('.remove-size');
            const sizeRows = document.querySelectorAll('.size-row');
            
            removeButtons.forEach(button => {
                button.disabled = sizeRows.length <= 1;
            });

            // Hide/show add button based on available sizes
            const addButton = document.getElementById('addSize');
            addButton.style.display = sizeRows.length >= maxSizes ? 'none' : 'inline-block';
        }

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const sizeSelects = document.querySelectorAll('select[name*="[size]"]');
            const priceInputs = document.querySelectorAll('input[name*="[price]"]');
            const selectedSizes = [];
            let hasError = false;

            // Check for duplicate sizes
            sizeSelects.forEach(select => {
                if (select.value && selectedSizes.includes(select.value)) {
                    alert('Please select different sizes. Duplicate sizes are not allowed.');
                    hasError = true;
                    return;
                }
                if (select.value) {
                    selectedSizes.push(select.value);
                }
            });

            // Check if at least one size is selected
            if (selectedSizes.length === 0) {
                alert('Please add at least one size for this dish.');
                hasError = true;
            }

            // Check prices
            priceInputs.forEach(input => {
                if (input.value && parseFloat(input.value) <= 0) {
                    alert('All prices must be greater than 0.');
                    hasError = true;
                }
            });

            if (hasError) {
                e.preventDefault();
            }
        });

        // Initialize
        updateRemoveButtons();
    </script>

@endsection
