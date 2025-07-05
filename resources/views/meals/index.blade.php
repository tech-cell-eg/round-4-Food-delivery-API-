@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">تصفية النتائج</h5>
                </div>
                <div class="card-body">
                    <form id="filter-form">
                        <!-- Country Filter -->
                        <div class="mb-3">
                            <label class="form-label">البلد</label>
                            <select name="country_id" class="form-select" onchange="this.form.submit()">
                                <option value="">كل البلدان</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ request('country_id') == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Category Filter -->
                        <div class="mb-3">
                            <label class="form-label">التصنيف</label>
                            <select name="category_id" class="form-select" onchange="this.form.submit()">
                                <option value="">كل التصنيفات</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Restaurant Filter -->
                        <div class="mb-3">
                            <label class="form-label">المطعم</label>
                            <select name="restaurant_id" class="form-select" onchange="this.form.submit()">
                                <option value="">كل المطاعم</option>
                                @foreach($restaurants as $restaurant)
                                    <option value="{{ $restaurant->id }}" {{ request('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
                                        {{ $restaurant->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-3">
                            <label class="form-label">نطاق السعر</label>
                            <div class="row g-2">
                                <div class="col">
                                    <input type="number" name="min_price" class="form-control" placeholder="الحد الأدنى" 
                                           value="{{ request('min_price') }}" onchange="this.form.submit()">
                                </div>
                                <div class="col">
                                    <input type="number" name="max_price" class="form-control" placeholder="الحد الأقصى" 
                                           value="{{ request('max_price') }}" onchange="this.form.submit()">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Meals Grid -->
        <div class="col-md-9">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @forelse($meals as $meal)
                <div class="col">
                    <div class="card h-100 meal-card">
                        <img src="{{ $meal->image_url }}" class="card-img-top" alt="{{ $meal->name }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title mb-0">{{ $meal->name }}</h5>
                                <span class="badge bg-primary">{{ $meal->category->name }}</span>
                            </div>
                            <p class="card-text text-muted small">{{ Str::limit($meal->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-primary">{{ number_format($meal->price, 2) }} ر.س</h6>
                                <span class="text-muted small">{{ $meal->restaurant->name }}</span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <button class="btn btn-primary w-100">أضف إلى السلة</button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">لا توجد وجبات متاحة حسب معايير البحث المحددة</div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($meals->hasPages())
            <div class="mt-4">
                {{ $meals->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .meal-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .meal-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .meal-card .card-img-top {
        height: 180px;
        object-fit: cover;
    }
    .card-footer {
        border-top: none;
        background-color: #f8f9fa;
    }
    .form-select, .form-control {
        border-radius: 8px;
        padding: 10px 15px;
    }
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
</style>
@endpush

@endsection
