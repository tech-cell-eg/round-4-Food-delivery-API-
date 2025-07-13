<tbody>
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
                    @if($order->orderItems && $order->orderItems->count() > 0)
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
                <p class="text-muted">No orders match your search criteria.</p>
            </div>
        </td>
    </tr>
@endif
</tbody>

<!-- Pagination -->
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