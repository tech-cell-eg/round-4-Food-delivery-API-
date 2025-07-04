@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif

            <div class="row g-3">
                <div class="col-md-4">
                    <a href="{{ route('admin.dishes.index') }}" class="text-decoration-none">
                        <div class="card text-center shadow-sm h-100">
                            <div class="card-body py-4">
                                <i class="fas fa-utensils fa-2x mb-2 text-primary"></i>
                                <h5 class="card-title">عدد الوجبات</h5>
                                <h3 class="fw-bold">{{ $dishesCount }}</h3>
                                <small class="text-muted">اضغط للعرض بالتفصيل</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection