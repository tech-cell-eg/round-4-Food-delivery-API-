@extends('dashboard.layouts.master')

@section("title", "Edit Coupon")

@section("content")

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">

            <!-- breadcrumb -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Edit Coupon</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Coupons</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Coupon</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end breadcrumb -->

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit Coupon - {{ $coupon->code }}</h5>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Back to Coupons
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Basic Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Coupon Code -->
                                        <div class="mb-3">
                                            <label for="code" class="form-label">Coupon Code <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $coupon->code) }}" placeholder="e.g., SAVE20">
                                            @error('code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Chef -->
                                        <div class="mb-3">
                                            <label for="chef_id" class="form-label">Chef <span class="text-danger">*</span></label>
                                            <select class="form-select @error('chef_id') is-invalid @enderror" id="chef_id" name="chef_id">
                                                <option value="">Select Chef</option>
                                                @foreach($chefs as $chef)
                                                    <option value="{{ $chef->id }}" {{ old('chef_id', $coupon->chef_id) == $chef->id ? 'selected' : '' }}>
                                                        {{ $chef->user->name }} ({{ $chef->user->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('chef_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Description -->
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Enter coupon description">{{ old('description', $coupon->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Discount & Settings -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Discount & Settings</h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Discount Type -->
                                        <div class="mb-3">
                                            <label for="discount_type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                                            <select class="form-select @error('discount_type') is-invalid @enderror" id="discount_type" name="discount_type">
                                                <option value="">Select Discount Type</option>
                                                <option value="percentage" {{ old('discount_type', $coupon->discount_type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                                <option value="fixed" {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed Amount (EGP)</option>
                                            </select>
                                            @error('discount_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Discount Value -->
                                        <div class="mb-3">
                                            <label for="discount_value" class="form-label">Discount Value <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" class="form-control @error('discount_value') is-invalid @enderror" id="discount_value" name="discount_value" value="{{ old('discount_value', $coupon->discount_value) }}" step="0.01" min="0.01" placeholder="0.00">
                                                <span class="input-group-text" id="discount_unit">
                                                    <span id="discount_symbol">{{ $coupon->discount_type == 'percentage' ? '%' : 'EGP' }}</span>
                                                </span>
                                            </div>
                                            @error('discount_value')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Expires At -->
                                        <div class="mb-3">
                                            <label for="expires_at" class="form-label">Expires At <span class="text-danger">*</span></label>
                                            <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" id="expires_at" name="expires_at" value="{{ old('expires_at', $coupon->expires_at->format('Y-m-d\TH:i')) }}">
                                            @error('expires_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Status -->
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    Active
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">Check this box to make the coupon active.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Usage Information -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Usage Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h4 class="text-primary">{{ $coupon->orders()->count() }}</h4>
                                                    <p class="mb-0">Times Used</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h4 class="text-success">{{ $coupon->orders()->sum('discount') }} EGP</h4>
                                                    <p class="mb-0">Total Discounts</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h4 class="text-info">{{ $coupon->created_at->format('Y-m-d') }}</h4>
                                                    <p class="mb-0">Created Date</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h4 class="text-{{ $coupon->isExpired() ? 'danger' : 'warning' }}">
                                                        {{ $coupon->isExpired() ? 'Expired' : $coupon->expires_at->diffForHumans() }}
                                                    </h4>
                                                    <p class="mb-0">Expires</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                                        <i class="bx bx-x"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save"></i> Update Coupon
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    // Update discount symbol based on type
    document.getElementById('discount_type').addEventListener('change', function() {
        const discountSymbol = document.getElementById('discount_symbol');
        const discountValue = document.getElementById('discount_value');
        
        if (this.value === 'percentage') {
            discountSymbol.textContent = '%';
            discountValue.placeholder = 'e.g., 20';
            discountValue.max = 100;
        } else if (this.value === 'fixed') {
            discountSymbol.textContent = 'EGP';
            discountValue.placeholder = 'e.g., 50.00';
            discountValue.max = 99999.99;
        }
    });

    // Set correct max value on page load
    document.addEventListener('DOMContentLoaded', function() {
        const discountType = document.getElementById('discount_type').value;
        const discountValue = document.getElementById('discount_value');
        
        if (discountType === 'percentage') {
            discountValue.max = 100;
        } else if (discountType === 'fixed') {
            discountValue.max = 99999.99;
        }
    });
</script>
@endsection 