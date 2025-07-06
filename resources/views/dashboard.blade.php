@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">لوحة التحكم</h3>
                    {{auth()->user()->type}}
                </div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <div class="row">
                        <!-- لوحة التحكم الخاصة بالشيف -->
                        @if(auth()->user()->type === 'chef')
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-utensils fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">إدارة الوجبات</h5>
                                    <p class="card-text">قم بإضافة وتعديل وحذف وجباتك</p>
                                    <a href="{{ route('chef.meals.index') }}" class="btn btn-outline-primary">الذهاب إلى الوجبات</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-line fa-3x text-success mb-3"></i>
                                    <h5 class="card-title">إحصائيات الطلبات</h5>
                                    <p class="card-text">عرض إحصائيات ومبيعات وجباتك</p>
                                    <a href="{{ route('chef.orders') }}" class="btn btn-outline-success">عرض الإحصائيات</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-comments fa-3x text-info mb-3"></i>
                                    <h5 class="card-title">المراسلات</h5>
                                    <p class="card-text">تواصل مع عملائك</p>
                                    <a href="{{ route('chat.index') }}" class="btn btn-outline-info">عرض المحادثات</a>
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- لوحة التحكم الخاصة بالزبون -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-shopping-cart fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">سلة المشتريات</h5>
                                    <p class="card-text">عرض وإدارة مشترياتك</p>
                                    <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">عرض السلة</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-history fa-3x text-success mb-3"></i>
                                    <h5 class="card-title">طلباتي السابقة</h5>
                                    <p class="card-text">عرض سجل طلباتك</p>
                                    <a href="{{ route('orders.history') }}" class="btn btn-outline-success">عرض الطلبات</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-heart fa-3x text-danger mb-3"></i>
                                    <h5 class="card-title">المفضلة</h5>
                                    <p class="card-text">عرض قائمة المفضلة لديك</p>
                                    <a href="{{ route('favorites') }}" class="btn btn-outline-danger">عرض المفضلة</a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection