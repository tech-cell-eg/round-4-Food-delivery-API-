@extends('dashboard.layouts.master')

@section("title", "Orders")

@section("content")

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">

            <!-- breadcrumb -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Orders</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Orders</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end breadcrumb -->

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-12 col-lg-3">
                    <div class="card radius-10 border-start border-0 border-3 border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Orders</p>
                                    <h4 class="my-1 text-info">{{ $stats['total'] }}</h4>
                                    <p class="mb-0 font-13">All Time</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto">
                                    <i class='bx bx-shopping-bag'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="card radius-10 border-start border-0 border-3 border-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Pending Orders</p>
                                    <h4 class="my-1 text-warning">{{ $stats['pending'] }}</h4>
                                    <p class="mb-0 font-13">Awaiting Processing</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto">
                                    <i class='bx bx-time'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="card radius-10 border-start border-0 border-3 border-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Completed Orders</p>
                                    <h4 class="my-1 text-success">{{ $stats['completed'] }}</h4>
                                    <p class="mb-0 font-13">Successfully Delivered</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                    <i class='bx bx-check-circle'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="card radius-10 border-start border-0 border-3 border-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Revenue</p>
                                    <h4 class="my-1 text-primary">${{ number_format($stats['total_revenue'], 2) }}</h4>
                                    <p class="mb-0 font-13">From Completed Orders</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                                    <i class='bx bx-dollar'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Stats Row -->
            <div class="row mb-4">
                <div class="col-12 col-lg-4">
                    <div class="card radius-10 border-start border-0 border-3 border-danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Today's Orders</p>
                                    <h4 class="my-1 text-danger">{{ $stats['today_orders'] }}</h4>
                                    <p class="mb-0 font-13">Orders Today</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                                    <i class='bx bx-calendar-event'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card radius-10 border-start border-0 border-3 border-secondary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Processing Orders</p>
                                    <h4 class="my-1 text-secondary">{{ $stats['processing'] }}</h4>
                                    <p class="mb-0 font-13">Currently Processing</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-moonlit text-white ms-auto">
                                    <i class='bx bx-loader-circle'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card radius-10 border-start border-0 border-3 border-dark">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Cancelled Orders</p>
                                    <h4 class="my-1 text-dark">{{ $stats['cancelled'] }}</h4>
                                    <p class="mb-0 font-13">Cancelled by Customer/Chef</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-blackberry text-white ms-auto">
                                    <i class='bx bx-x-circle'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table mb-0" id="ordersTable">
                            <thead class="table-light">
                            <tr>
                                <th>Order Info</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="ordersTableBody">
                            @if(isset($orders) && $orders->count() > 0)
                                @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="recent-product-img">
                                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="bx bx-receipt text-primary"></i>
                                                    </div>
                                                </div>
                                                <div class="ms-2">
                                                    <h6 class="mb-1 font-14">{{ $order->order_number }}</h6>
                                                    <p class="mb-0 font-13 text-secondary">ID: {{ $order->id }}</p>
                                                    @if($order->payment)
                                                        <small class="text-muted">{{ ucfirst($order->payment->payment_method) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($order->customer && $order->customer->user)
                                                    <div class="recent-product-img">
                                                        <img src="{{ $order->customer->user->profile_image ? asset('storage/' . $order->customer->user->profile_image) : asset('assets/images/avatars/avatar-1.png') }}"
                                                             alt="{{ $order->customer->user->name }}"
                                                             class="rounded-circle"
                                                             width="35"
                                                             height="35">
                                                    </div>
                                                    <div class="ms-2">
                                                        <h6 class="mb-1 font-14">{{ $order->customer->user->name }}</h6>
                                                        <p class="mb-0 font-13 text-secondary">{{ $order->customer->user->email }}</p>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Customer not found</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-1 font-14">{{ $order->order_items_count }} Items</h6>
                                                @if($order->orderItems->count() > 0)
                                                    @foreach($order->orderItems->take(2) as $item)
                                                        <p class="mb-0 font-13 text-secondary">
                                                            {{ $item->quantity }}x {{ $item->dish->name ?? 'Unknown Dish' }}
                                                        </p>
                                                    @endforeach
                                                    @if($order->orderItems->count() > 2)
                                                        <small class="text-muted">+{{ $order->orderItems->count() - 2 }} more</small>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-1 font-14">${{ number_format($order->total, 2) }}</h6>
                                                @if($order->coupon)
                                                    <p class="mb-0 font-13 text-success">
                                                        <i class="bx bx-tag me-1"></i>{{ $order->coupon->code }}
                                                    </p>
                                                @endif
                                                @if($order->discount > 0)
                                                    <small class="text-success">-${{ number_format($order->discount, 2) }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match($order->status) {
                                                    'pending' => 'warning',
                                                    'processing' => 'info',
                                                    'completed' => 'success',
                                                    'cancelled' => 'danger',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <div class="badge rounded-pill text-{{ $statusClass }} bg-light-{{ $statusClass }} p-2 text-uppercase px-3">
                                                <i class='bx bxs-circle me-1'></i>{{ ucfirst($order->status) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-1 font-14">{{ $order->created_at->format('M d, Y') }}</h6>
                                                <p class="mb-0 font-13 text-secondary">{{ $order->created_at->format('h:i A') }}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <!-- View Button -->
                                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="View Details">
                                                    <i class='bx bx-show'></i>
                                                </a>

                                                <!-- Status Update Dropdown -->
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class='bx bx-cog'></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if($order->status === 'pending')
                                                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="updateOrderStatus({{ $order->id }}, 'processing')">
                                                                <i class='bx bx-loader-circle me-1'></i>Mark as Processing
                                                            </a></li>
                                                        @endif
                                                        @if(in_array($order->status, ['pending', 'processing']))
                                                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="updateOrderStatus({{ $order->id }}, 'completed')">
                                                                <i class='bx bx-check-circle me-1'></i>Mark as Completed
                                                            </a></li>
                                                            <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="updateOrderStatus({{ $order->id }}, 'cancelled')">
                                                                <i class='bx bx-x-circle me-1'></i>Cancel Order
                                                            </a></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bx bx-shopping-bag display-1 text-muted mb-3"></i>
                                            <h5 class="text-muted">No Orders Found</h5>
                                            <p class="text-muted">No orders have been placed yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div id="paginationWrapper">
                        @if(isset($orders) && method_exists($orders, 'links') && $orders->hasPages())
                            <div class="mt-4 d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <span class="text-muted">
                                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
                                    </span>
                                </div>
                                <div class="pagination-wrapper">
                                    {{ $orders->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>



        </div>
    </div>
    <!--end page wrapper -->

        <script>
        let searchTimeout;

        $(document).ready(function() {
            // Search functionality
            $('#searchInput').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    performSearch();
                }, 500);
            });

            // Date filter functionality
            $('#dateFrom, #dateTo').on('change', function() {
                performSearch();
            });

            // Status filter functionality
            $('.status-filter').on('click', function() {
                $('.status-filter').removeClass('active');
                $(this).addClass('active');
                performSearch();
            });
        });

        function performSearch() {
            const query = $('#searchInput').val();
            const status = $('.status-filter.active').data('status');
            const dateFrom = $('#dateFrom').val();
            const dateTo = $('#dateTo').val();

            // Show loading state
            $('#ordersTableBody').html(`
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Searching orders...</p>
                    </td>
                </tr>
            `);

            // Make AJAX request
            $.ajax({
                url: '{{ route("admin.orders.search") }}',
                method: 'GET',
                data: {
                    query: query,
                    status: status,
                    date_from: dateFrom,
                    date_to: dateTo
                },
                success: function(response) {
                    if (response.success) {
                        $('#ordersTableBody').html(response.html);
                        updatePagination(response.data);
                    } else {
                        showError('Error loading orders');
                    }
                },
                error: function(xhr) {
                    console.error('Search error:', xhr);
                    showError('Error performing search');
                }
            });
        }

        function updatePagination(data) {
            // Update pagination info
            if (data.total > 0) {
                const paginationInfo = `
                    <div class="mt-4 d-flex justify-content-between align-items-center">
                        <div class="pagination-info">
                            <span class="text-muted">
                                Showing ${data.from} to ${data.to} of ${data.total} results
                            </span>
                        </div>
                    </div>
                `;
                $('#paginationWrapper').html(paginationInfo);
            } else {
                $('#paginationWrapper').html('');
            }
        }

        function updateOrderStatus(orderId, status) {
            if (confirm('Are you sure you want to update this order status?')) {
                // Show loading indicator
                const button = event.target.closest('.dropdown').querySelector('.dropdown-toggle');
                const originalContent = button.innerHTML;
                button.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i>';
                button.disabled = true;

                fetch(`/admin/orders/${orderId}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccess(data.message);
                        // Refresh the current view
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        showError('Error updating order status');
                        // Restore button
                        button.innerHTML = originalContent;
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('Error updating order status');
                    // Restore button
                    button.innerHTML = originalContent;
                    button.disabled = false;
                });
            }
        }

        function showSuccess(message) {
            // Create success toast/alert
            const alert = $(`
                <div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
                    <i class="bx bx-check-circle me-2"></i>${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
            $('body').append(alert);

            // Auto dismiss after 3 seconds
            setTimeout(() => {
                alert.alert('close');
            }, 3000);
        }

        function showError(message) {
            // Create error toast/alert
            const alert = $(`
                <div class="alert alert-danger alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
                    <i class="bx bx-error-circle me-2"></i>${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
            $('body').append(alert);

            // Auto dismiss after 3 seconds
            setTimeout(() => {
                alert.alert('close');
            }, 3000);
        }

        // Clear search functionality
        function clearSearch() {
            $('#searchInput').val('');
            $('#dateFrom').val('');
            $('#dateTo').val('');
            $('.status-filter').removeClass('active');
            $('.status-filter[data-status=""]').addClass('active');
            performSearch();
        }

        // Export functionality (placeholder)
        function exportOrders() {
            const query = $('#searchInput').val();
            const status = $('.status-filter.active').data('status');
            const dateFrom = $('#dateFrom').val();
            const dateTo = $('#dateTo').val();

            // Build export URL with current filters
            let exportUrl = '/admin/orders/export?';
            if (query) exportUrl += `query=${encodeURIComponent(query)}&`;
            if (status) exportUrl += `status=${status}&`;
            if (dateFrom) exportUrl += `date_from=${dateFrom}&`;
            if (dateTo) exportUrl += `date_to=${dateTo}&`;

            // For now, just show a message
            showSuccess('Export functionality will be implemented soon');
        }

        // Print functionality
        function printOrders() {
            window.print();
        }
    </script>

@endsection
