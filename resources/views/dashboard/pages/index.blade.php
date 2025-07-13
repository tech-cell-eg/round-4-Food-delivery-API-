@extends('dashboard.layouts.master')

@section("title", "Admin Dashboard")

@section("content")

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-4 border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Orders</p>
                                    <h4 class="my-1 text-info">{{ number_format($totalOrders) }}</h4>
                                    <p class="mb-0 font-13">Completed Orders: {{ number_format($completedOrders) }}</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='bx bxs-cart'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card radius-10 border-start border-0 border-4 border-danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Revenue</p>
                                    <h4 class="my-1 text-danger">${{ number_format($totalRevenue, 2) }}</h4>
                                    <p class="mb-0 font-13">Pending Payments: ${{ number_format($pendingPayments, 2) }}</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i class='bx bxs-wallet'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card radius-10 border-start border-0 border-4 border-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Average Rating</p>
                                    <h4 class="my-1 text-success">{{ number_format($averageRating, 1) ?? 'N/A' }}/5</h4>
                                    <p class="mb-0 font-13">Total Reviews: {{ number_format($totalReviews) }}</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-star' ></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card radius-10 border-start border-0 border-4 border-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Customers</p>
                                    <h4 class="my-1 text-warning">{{ number_format($totalCustomers) }}</h4>
                                    <p class="mb-0 font-13">Total Users: {{ number_format($totalUsers) }}</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class='bx bxs-group'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end row-->

            <!-- Additional Statistics -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 mt-4">
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-4 border-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Chefs</p>
                                    <h4 class="my-1 text-primary">{{ number_format($totalChefs) }}</h4>
                                    <p class="mb-0 font-13">Verified: {{ number_format($verifiedChefs) }}</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='bx bxs-user-badge'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card radius-10 border-start border-0 border-4 border-secondary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Dishes</p>
                                    <h4 class="my-1 text-secondary">{{ number_format($totalDishes) }}</h4>
                                    <p class="mb-0 font-13">Categories: {{ number_format($totalCategories) }}</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i class='bx bxs-food-menu'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card radius-10 border-start border-0 border-4 border-dark">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Active Carts</p>
                                    <h4 class="my-1 text-dark">{{ number_format($activeCarts) }}</h4>
                                    <p class="mb-0 font-13">Total Carts: {{ number_format($totalCarts) }}</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-shopping-bags'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card radius-10 border-start border-0 border-4 border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Recent Orders</p>
                                    <h4 class="my-1 text-info">{{ number_format($recentOrders) }}</h4>
                                    <p class="mb-0 font-13">Last 7 days</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class='bx bxs-time-five'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end row-->

            <!-- Order Status Statistics -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card radius-10">
                        <div class="card-header">
                            <h5 class="mb-0">Order Status Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="row row-cols-1 row-cols-md-4 g-3">
                                <div class="col">
                                    <div class="text-center">
                                        <div class="circle-progress bg-success-light text-success mb-3">
                                            <h4 class="mb-0">{{ number_format($completedOrders) }}</h4>
                                        </div>
                                        <p class="mb-0">Completed</p>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="text-center">
                                        <div class="circle-progress bg-warning-light text-warning mb-3">
                                            <h4 class="mb-0">{{ number_format($pendingOrders) }}</h4>
                                        </div>
                                        <p class="mb-0">Pending</p>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="text-center">
                                        <div class="circle-progress bg-info-light text-info mb-3">
                                            <h4 class="mb-0">{{ number_format($processingOrders) }}</h4>
                                        </div>
                                        <p class="mb-0">Processing</p>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="text-center">
                                        <div class="circle-progress bg-danger-light text-danger mb-3">
                                            <h4 class="mb-0">{{ number_format($cancelledOrders) }}</h4>
                                        </div>
                                        <p class="mb-0">Cancelled</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end row-->


            <div class="row">
                <div class="col-12 col-lg-8 d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h6 class="mb-0">System Overview</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center ms-auto font-13 gap-2 mb-3">
                                <span class="border px-1 rounded"><i class="bx bxs-circle me-1" style="color: #14abef"></i>Sales</span>
                                <span class="border px-1 rounded"><i class="bx bxs-circle me-1" style="color: #ffc107"></i>Orders</span>
                            </div>
                            <div class="text-center mt-5">
                                <h3 class="text-primary mb-2">Welcome to Food Delivery Admin Dashboard</h3>
                                <p class="text-muted">Comprehensive view of system statistics</p>
                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <div class="text-center mb-3">
                                            <i class="bx bxs-cart-alt font-30 text-info"></i>
                                            <p class="mb-0 mt-2">Integrated Order System</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center mb-3">
                                            <i class="bx bxs-user-badge font-30 text-success"></i>
                                            <p class="mb-0 mt-2">Chef & Customer Management</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center mb-3">
                                            <i class="bx bxs-bar-chart-alt-2 font-30 text-warning"></i>
                                            <p class="mb-0 mt-2">Reports & Analytics</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-cols-1 row-cols-md-3 row-cols-xl-3 g-0 row-group text-center border-top">
                            <div class="col">
                                <div class="p-3">
                                    <h5 class="mb-0">{{ number_format($totalUsers) }}</h5>
                                    <small class="mb-0">Total Users</small>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-3">
                                    <h5 class="mb-0">${{ number_format($totalRevenue, 0) }}</h5>
                                    <small class="mb-0">Total Revenue</small>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-3">
                                    <h5 class="mb-0">{{ number_format($totalDishes) }}</h5>
                                    <small class="mb-0">Total Dishes</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h6 class="mb-0">Quick Statistics</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <div class="mb-4">
                                    <h4 class="text-primary">{{ number_format($averageRating, 1) ?? 'N/A' }}</h4>
                                    <p class="mb-0 text-muted">Average Rating</p>
                                    <div class="mt-2">
                                        @if($averageRating)
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $averageRating)
                                                    <i class="bx bxs-star text-warning"></i>
                                                @else
                                                    <i class="bx bx-star text-muted"></i>
                                                @endif
                                            @endfor
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted">From {{ number_format($totalReviews) }} reviews</small>
                                </div>
                            </div>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center border-top">Verified Chefs <span class="badge bg-success rounded-pill">{{ number_format($verifiedChefs) }}</span>
                            </li>
                            <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">Active Carts <span class="badge bg-info rounded-pill">{{ number_format($activeCarts) }}</span>
                            </li>
                            <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">Categories <span class="badge bg-primary rounded-pill">{{ number_format($totalCategories) }}</span>
                            </li>
                            <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">Ingredients <span class="badge bg-warning text-dark rounded-pill">{{ number_format($totalIngredients) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div><!--end row-->


            <div class="row row-cols-1 row-cols-lg-3">
                <div class="col d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-body">
                            <p class="font-weight-bold mb-1 text-secondary">Weekly Revenue</p>
                            <div class="d-flex align-items-center mb-4">
                                <div>
                                    <h4 class="mb-0">${{ number_format($recentRevenue, 2) }}</h4>
                                </div>
                                <div class="">
                                    <p class="mb-0 align-self-center font-weight-bold text-info ms-2">Last 7 days <i class="bx bxs-time mr-2"></i>
                                    </p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <p class="mb-1 text-secondary">Total Revenue: ${{ number_format($totalRevenue, 2) }}</p>
                                <p class="mb-0 text-secondary">Orders Value: ${{ number_format($totalOrdersValue, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-header bg-transparent">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h6 class="mb-0">Orders Summary</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mt-3">
                                <p class="mb-2">Total Orders: <strong>{{ number_format($totalOrders) }}</strong></p>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $totalOrders > 0 ? ($completedOrders / $totalOrders) * 100 : 0 }}%;"></div>
                                </div>
                                <small class="text-muted">Completion Rate: {{ $totalOrders > 0 ? number_format(($completedOrders / $totalOrders) * 100, 1) : 0 }}%</small>
                            </div>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center border-top">Completed <span class="badge bg-gradient-quepal rounded-pill">{{ number_format($completedOrders) }}</span>
                            </li>
                            <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">Pending <span class="badge bg-gradient-ibiza rounded-pill">{{ number_format($pendingOrders) }}</span>
                            </li>
                            <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">Processing <span class="badge bg-gradient-deepblue rounded-pill">{{ number_format($processingOrders) }}</span>
                            </li>
                            <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">Cancelled <span class="badge bg-gradient-burning rounded-pill">{{ number_format($cancelledOrders) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-header bg-transparent">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h6 class="mb-0">System Statistics</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <div class="mb-3">
                                    <h5 class="text-primary">{{ number_format($totalIngredients) }}</h5>
                                    <p class="mb-1 text-secondary">Ingredients</p>
                                </div>
                                <div class="mb-3">
                                    <h5 class="text-success">{{ number_format($totalCoupons) }}</h5>
                                    <p class="mb-1 text-secondary">Coupons</p>
                                </div>
                            </div>
                        </div>
                        <div class="row row-group border-top g-0">
                            <div class="col">
                                <div class="p-3 text-center">
                                    <h4 class="mb-0 text-primary">{{ number_format($totalAdmins) }}</h4>
                                    <p class="mb-0">Admins</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-3 text-center">
                                    <h4 class="mb-0 text-success">{{ number_format($verifiedChefs) }}</h4>
                                    <p class="mb-0">Verified Chefs</p>
                                </div>
                            </div>
                        </div><!--end row-->
                    </div>
                </div>
            </div><!--end row-->

        </div>
    </div>
    <!--end page wrapper -->


@endsection
