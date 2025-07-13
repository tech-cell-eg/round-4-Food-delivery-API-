@extends('dashboard.layouts.master')

@section("title", "Order Details - " . $order->order_number)

@section("content")

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">

            <!-- breadcrumb -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Order Details</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $order->order_number }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end breadcrumb -->

            <!-- Order Header -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h4 class="mb-0">Order {{ $order->order_number }}</h4>
                                    <p class="text-muted mb-0">Order placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    @php
                                        $statusClass = match($order->status) {
                                            'pending' => 'warning',
                                            'processing' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }} px-3 py-2">{{ ucfirst($order->status) }}</span>

                                    <!-- Status Update Dropdown -->
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            Update Status
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if($order->status === 'pending')
                                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="updateStatus('processing')">
                                                    <i class='bx bx-loader-circle me-1'></i>Mark as Processing
                                                </a></li>
                                            @endif
                                            @if(in_array($order->status, ['pending', 'processing']))
                                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="updateStatus('completed')">
                                                    <i class='bx bx-check-circle me-1'></i>Mark as Completed
                                                </a></li>
                                                <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="updateStatus('cancelled')">
                                                    <i class='bx bx-x-circle me-1'></i>Cancel Order
                                                </a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Details -->
            <div class="row">
                <!-- Customer Information -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Customer Information</h5>
                            @if($order->customer && $order->customer->user)
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $order->customer->user->profile_image ? asset('storage/' . $order->customer->user->profile_image) : asset('assets/images/avatars/avatar-1.png') }}"
                                         alt="{{ $order->customer->user->name }}"
                                         class="rounded-circle me-3"
                                         width="50" height="50">
                                    <div>
                                        <h6 class="mb-0">{{ $order->customer->user->name }}</h6>
                                        <small class="text-muted">Customer ID: {{ $order->customer->id }}</small>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <i class="bx bx-envelope me-2 text-primary"></i>
                                    <span>{{ $order->customer->user->email }}</span>
                                </div>
                                @if($order->customer->user->phone)
                                    <div class="mb-2">
                                        <i class="bx bx-phone me-2 text-success"></i>
                                        <span>{{ $order->customer->user->phone }}</span>
                                    </div>
                                @endif
                                @if($order->customer->user->bio)
                                    <div class="mb-2">
                                        <i class="bx bx-info-circle me-2 text-info"></i>
                                        <span>{{ $order->customer->user->bio }}</span>
                                    </div>
                                @endif
                            @else
                                <p class="text-muted">Customer information not available</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Delivery Information -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Delivery Information</h5>
                            @if($order->address)
                                <div class="mb-2">
                                    <i class="bx bx-map me-2 text-primary"></i>
                                    <span>{{ $order->address->address_text }}</span>
                                </div>
                                @if($order->address->street)
                                    <div class="mb-2">
                                        <i class="bx bx-street-view me-2 text-secondary"></i>
                                        <span>{{ $order->address->street }}</span>
                                    </div>
                                @endif
                                @if($order->address->appartment)
                                    <div class="mb-2">
                                        <i class="bx bx-building me-2 text-info"></i>
                                        <span>Apartment: {{ $order->address->appartment }}</span>
                                    </div>
                                @endif
                                @if($order->address->post_code)
                                    <div class="mb-2">
                                        <i class="bx bx-mail-send me-2 text-warning"></i>
                                        <span>{{ $order->address->post_code }}</span>
                                    </div>
                                @endif
                                @if($order->address->lable)
                                    <div class="mb-2">
                                        <i class="bx bx-label me-2 text-success"></i>
                                        <span>{{ $order->address->lable }}</span>
                                    </div>
                                @endif
                            @else
                                <p class="text-muted">Delivery address not available</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Payment Information</h5>
                            @if($order->payment)
                                <div class="mb-2">
                                    <i class="bx bx-credit-card me-2 text-primary"></i>
                                    <span>{{ ucfirst($order->payment->payment_method) }}</span>
                                </div>
                                <div class="mb-2">
                                    <i class="bx bx-check-circle me-2 text-success"></i>
                                    <span>{{ ucfirst($order->payment->status) }}</span>
                                </div>
                                @if($order->payment->transaction_id)
                                    <div class="mb-2">
                                        <i class="bx bx-receipt me-2 text-info"></i>
                                        <span>{{ $order->payment->transaction_id }}</span>
                                    </div>
                                @endif
                                <div class="mb-2">
                                    <i class="bx bx-dollar me-2 text-success"></i>
                                    <span>${{ number_format($order->payment->amount, 2) }}</span>
                                </div>
                            @else
                                <p class="text-muted">Payment information not available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order Items</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th>Chef</th>
                                        <th>Size</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($order->orderItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->dish && $item->dish->image)
                                                        <img src="{{ asset('storage/' . $item->dish->image) }}"
                                                             alt="{{ $item->dish->name }}"
                                                             class="rounded me-3"
                                                             width="40" height="40">
                                                    @else
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                             style="width: 40px; height: 40px;">
                                                            <i class="bx bx-food-menu text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ $item->dish->name ?? 'Unknown Dish' }}</h6>
                                                        <small class="text-muted">ID: {{ $item->dish->id ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($item->dish && $item->dish->chef && $item->dish->chef->user)
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $item->dish->chef->user->profile_image ? asset('storage/' . $item->dish->chef->user->profile_image) : asset('assets/images/avatars/avatar-1.png') }}"
                                                             alt="{{ $item->dish->chef->user->name }}"
                                                             class="rounded-circle me-2"
                                                             width="30" height="30">
                                                        <span>{{ $item->dish->chef->user->name }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Unknown Chef</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->size ?? 'Regular' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>${{ number_format($item->unit_price, 2) }}</td>
                                            <td><strong>${{ number_format($item->total_price, 2) }}</strong></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No items found</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary & Notes -->
            <div class="row">
                <div class="col-lg-6">
                    @if($order->notes)
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Order Notes</h5>
                                <p class="mb-0">{{ $order->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order Summary</h5>
                            <div class="row">
                                <div class="col-8">
                                    <p class="mb-2">Subtotal:</p>
                                    @if($order->discount > 0)
                                        <p class="mb-2">Discount:</p>
                                    @endif
                                    <p class="mb-2">Delivery Fee:</p>
                                    <p class="mb-2">Tax:</p>
                                    <hr>
                                    <p class="mb-0"><strong>Total:</strong></p>
                                </div>
                                <div class="col-4 text-end">
                                    <p class="mb-2">${{ number_format($order->subtotal, 2) }}</p>
                                    @if($order->discount > 0)
                                        <p class="mb-2 text-success">-${{ number_format($order->discount, 2) }}</p>
                                    @endif
                                    <p class="mb-2">${{ number_format($order->delivery_fee, 2) }}</p>
                                    <p class="mb-2">${{ number_format($order->tax, 2) }}</p>
                                    <hr>
                                    <p class="mb-0"><strong>${{ number_format($order->total, 2) }}</strong></p>
                                </div>
                            </div>
                            @if($order->coupon)
                                <div class="mt-3 p-2 bg-light rounded">
                                    <small class="text-success">
                                        <i class="bx bx-tag me-1"></i>
                                        Coupon Applied: {{ $order->coupon->code }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status History -->
            @if($order->statusHistories && $order->statusHistories->count() > 0)
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Status History</h5>
                                <div class="timeline">
                                    @foreach($order->statusHistories->sortByDesc('created_at') as $history)
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-{{ match($history->status) {
                                                'pending' => 'warning',
                                                'processing' => 'info',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                                default => 'secondary'
                                            } }}"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">{{ ucfirst($history->status) }}</h6>
                                                <p class="mb-1">{{ $history->note }}</p>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($history->created_at)->format('M d, Y \a\t h:i A') }}
                                                @if($history->user)
                                                        by {{ $history->user->name }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                                    <i class="bx bx-arrow-back me-1"></i>Back to Orders
                                </a>
                                <div class="d-flex gap-2">
                                    @if($order->status !== 'completed')
                                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this order?')">
                                                <i class="bx bx-trash me-1"></i>Delete Order
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--end page wrapper -->

    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }

        .timeline-marker {
            position: absolute;
            left: -37px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 3px #e9ecef;
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 3px solid #e9ecef;
        }

        @media print {
            .btn, .dropdown, .breadcrumb, .card-title {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>

    <script>
        function updateStatus(status) {
            if (confirm('Are you sure you want to update this order status?')) {
                fetch(`{{ route('admin.orders.updateStatus', $order->id) }}`, {
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
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error updating order status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating order status');
                });
            }
        }
    </script>

@endsection
