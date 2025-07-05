@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">قائمة الوجبات</h1>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach($dishes as $dish)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="position-relative">
                    <img src="https://placehold.co/400x250?text={{ $dish->name }}" class="card-img-top" alt="{{ $dish->name }}">
                    <span class="position-absolute top-0 start-0 m-2 badge {{ $dish->is_available ? 'bg-success' : 'bg-secondary' }}">
                        {{ $dish->is_available ? 'متاحة' : 'غير متاحة' }}
                    </span>
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-1 text-truncate">{{ $dish->name }}</h5>
                    <p class="small text-muted mb-1"><i class="fas fa-user me-1"></i>{{ $dish->chef?->user?->name ?? '—' }}</p>
                    <p class="small text-muted mb-2"><i class="fas fa-tag me-1"></i>{{ $dish->category?->name ?? '—' }}</p>

                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-star text-warning me-1"></i>{{ number_format($dish->avg_rate,1) }}</span>
                        <form action="{{ route('cart.items.store') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="dish_id" value="{{ $dish->id }}">
                            <input type="hidden" name="size_name" value="medium">
                            <button class="btn btn-sm btn-primary">أضف للسلة</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection