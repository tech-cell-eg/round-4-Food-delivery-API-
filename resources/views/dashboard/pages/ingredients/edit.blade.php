@extends('dashboard.layouts.master')

@section("title", "Edit Ingredient")

@section("content")

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">

            <!-- breadcrumb -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Ingredients</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.ingredients.index') }}">Ingredients</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Ingredient - ({{ $ingredient->name }})</li>
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
                                <div><i class="bx bx-food-menu me-1 font-22 text-primary"></i></div>
                                <h5 class="mb-0 text-primary">Edit Ingredient</h5>
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

                            <!-- Ingredient Edit Form -->
                            <form action="{{ route('admin.ingredients.update', $ingredient->id) }}" method="POST" class="row g-3">
                                @csrf
                                @method("PATCH")

                                <div class="col-12">
                                    <label for="name" class="form-label">Ingredient Name *</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $ingredient->name) }}"
                                           placeholder="Enter ingredient name (e.g., Chicken, Tomatoes, Apples)"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="type" class="form-label">Ingredient Type *</label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" 
                                            name="type" 
                                            required>
                                        <option value="">Select ingredient type</option>
                                        <option value="basic" {{ old('type', $ingredient->type) == 'basic' ? 'selected' : '' }}>Basic Ingredient</option>
                                        <option value="fruit" {{ old('type', $ingredient->type) == 'fruit' ? 'selected' : '' }}>Fruit</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Basic ingredients include vegetables, meats, spices, etc. Fruits are categorized separately.</small>
                                </div>

                                <!-- Display usage info -->
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="bx bx-info-circle me-2"></i>
                                        <strong>Usage Information:</strong> This ingredient is currently used in 
                                        <strong>{{ $ingredient->dishes()->count() }} dish(es)</strong>.
                                        @if($ingredient->dishes()->count() > 0)
                                            Changing the type or name will affect all dishes using this ingredient.
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12">
                                    <hr>
                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-primary px-5">
                                            <i class="bx bxs-save me-1"></i>Update Ingredient
                                        </button>
                                        <a href="{{ route('admin.ingredients.index') }}" class="btn btn-light px-5">
                                            <i class="bx bx-x me-1"></i>Cancel
                                        </a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                    <!-- Ingredient Examples Card -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h6 class="card-title text-info">
                                <i class="bx bx-info-circle me-1"></i>Ingredient Examples by Type
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Basic Ingredients:</strong>
                                    <ul class="list-unstyled ms-3">
                                        <li><code>Chicken</code> - Protein</li>
                                        <li><code>Rice</code> - Grain</li>
                                        <li><code>Tomatoes</code> - Vegetable</li>
                                        <li><code>Olive Oil</code> - Fat/Oil</li>
                                        <li><code>Garlic</code> - Spice/Herb</li>
                                        <li><code>Onion</code> - Vegetable</li>
                                        <li><code>Salt</code> - Seasoning</li>
                                        <li><code>Black Pepper</code> - Spice</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <strong>Fruit Ingredients:</strong>
                                    <ul class="list-unstyled ms-3">
                                        <li><code>Apples</code> - Fresh fruit</li>
                                        <li><code>Bananas</code> - Fresh fruit</li>
                                        <li><code>Oranges</code> - Citrus fruit</li>
                                        <li><code>Lemons</code> - Citrus fruit</li>
                                        <li><code>Strawberries</code> - Berry</li>
                                        <li><code>Mangoes</code> - Tropical fruit</li>
                                        <li><code>Dates</code> - Dried fruit</li>
                                        <li><code>Grapes</code> - Fresh fruit</li>
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
