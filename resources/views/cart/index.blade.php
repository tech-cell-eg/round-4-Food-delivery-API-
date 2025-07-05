@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">سلة التسوق</h3>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(!$cart || $cart->items->isEmpty())
    <div class="alert alert-info">سلتك فارغة حالياً.</div>
    <a href="{{ route('admin-dishes-index') }}" class="btn btn-primary">استكشاف الوجبات</a>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>الوجبة</th>
                <th>الحجم</th>
                <th>الكمية</th>
                <th>السعر</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($cart->items as $item)
            <tr>
                <td>{{ $item->dish->name }}</td>
                <td>{{ $item->size_name }}</td>
                <td>
                    <form action="{{ route('cart.items.update', $item->id) }}" method="POST" class="d-flex">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control form-control-sm w-50 me-2">
                        <button class="btn btn-sm btn-outline-secondary">تحديث</button>
                    </form>
                </td>
                <td>{{ $item->price * $item->quantity }} ر.س</td>
                <td>
                    <form action="{{ route('cart.items.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-between">
        <div>
            <strong>الإجمالي: {{ $total }} ر.س</strong>
        </div>
        <div>
            <a href="{{ route('checkout.index') }}" class="btn btn-success">إتمام الطلب</a>
            <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-outline-danger">تفريغ السلة</button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection