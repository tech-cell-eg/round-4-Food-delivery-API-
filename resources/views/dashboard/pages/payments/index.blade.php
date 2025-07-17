@extends('dashboard.layouts.master')

@section("title", "Payments")

@section("content")

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">

            <!-- breadcrumb -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Payments</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Payments</li>
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
                                    <p class="mb-0 text-secondary">Total Payments</p>
                                    <h4 class="my-1 text-info">{{ $stats['total'] }}</h4>
                                    <p class="mb-0 font-13">All Payments</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto">
                                    <i class='bx bx-credit-card'></i>
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
                                    <p class="mb-0 text-secondary">Completed Payments</p>
                                    <h4 class="my-1 text-success">{{ $stats['completed'] }}</h4>
                                    <p class="mb-0 font-13">Successfully Completed</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                    <i class='bx bx-check-circle'></i>
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
                                    <p class="mb-0 text-secondary">Pending Payments</p>
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
                    <div class="card radius-10 border-start border-0 border-3 border-danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Failed Payments</p>
                                    <h4 class="my-1 text-danger">{{ $stats['failed'] }}</h4>
                                    <p class="mb-0 font-13">Failed</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                                    <i class='bx bx-x-circle'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Stats Cards -->
            <div class="row mb-4">
                <div class="col-12 col-lg-4">
                    <div class="card radius-10 border-start border-0 border-3 border-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Revenue</p>
                                    <h4 class="my-1 text-primary">${{ number_format($stats['total_revenue'], 2) }}</h4>
                                    <p class="mb-0 font-13">Completed Payments</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                                    <i class='bx bx-dollar'></i>
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
                                    <p class="mb-0 text-secondary">Pending Amount</p>
                                    <h4 class="my-1 text-secondary">${{ number_format($stats['pending_amount'], 2) }}</h4>
                                    <p class="mb-0 font-13">Awaiting Confirmation</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-moonlit text-white ms-auto">
                                    <i class='bx bx-hourglass'></i>
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
                                    <p class="mb-0 text-secondary">This Month Revenue</p>
                                    <h4 class="my-1 text-dark">${{ number_format($stats['this_month_revenue'], 2) }}</h4>
                                    <p class="mb-0 font-13">{{ date('F Y') }}</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto">
                                    <i class='bx bx-trending-up'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payments Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Payments</h5>
                    <div>
                        <span class="badge bg-info">{{ $payments->total() }} payments</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order Number</th>
                                    <th>Customer</th>
                                    <th>Chef</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Transaction ID</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->id }}</td>
                                        <td>
                                            @if($payment->order)
                                                <a href="#" class="fw-bold text-primary">{{ $payment->order->order_number ?? 'ORD-' . $payment->order->id }}</a>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($payment->order && $payment->order->customer && $payment->order->customer->user)
                                                <div class="d-flex align-items-center">
                                                    <div class="recent-product-img">
                                                        @if($payment->order->customer->user->profile_image)
                                                            <img src="{{ asset('storage/' . $payment->order->customer->user->profile_image) }}" alt="customer" class="rounded-circle" width="40" height="40">
                                                        @else
                                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                <i class="bx bx-user text-white"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="ms-2">
                                                        <h6 class="mb-1 fw-bold">{{ $payment->order->customer->user->name }}</h6>
                                                        <p class="mb-0 font-13 text-muted">{{ $payment->order->customer->user->email }}</p>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($payment->order && $payment->order->chef && $payment->order->chef->user)
                                                <h6 class="mb-1 fw-bold">{{ $payment->order->chef->user->name }}</h6>
                                                <p class="mb-0 font-13 text-muted">{{ $payment->order->chef->user->email }}</p>

                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-success">${{ number_format($payment->amount, 2) }}</td>
                                        <td>
                                            @switch($payment->payment_method)
                                                @case('credit_card')
                                                    <span class="badge bg-primary">Credit Card</span>
                                                    @break
                                                @case('debit_card')
                                                    <span class="badge bg-info">Debit Card</span>
                                                    @break
                                                @case('cash_on_delivery')
                                                    <span class="badge bg-success">Cash on Delivery</span>
                                                    @break
                                                @case('wallet')
                                                    <span class="badge bg-warning">E-Wallet</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $payment->payment_method }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @switch($payment->status)
                                                @case('completed')
                                                    <span class="badge bg-success">Completed</span>
                                                    @break
                                                @case('pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                    @break
                                                @case('failed')
                                                    <span class="badge bg-danger">Failed</span>
                                                    @break
                                                @case('refunded')
                                                    <span class="badge bg-secondary">Refunded</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-light text-dark">{{ $payment->status }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($payment->transaction_id)
                                                <small class="text-muted">{{ $payment->transaction_id }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $payment->created_at->format('Y/m/d H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex order-actions">
                                                <a href="javascript:;" onclick="viewPayment({{ $payment->id }})" class="" title="View Payment"><i class='bx bxs-show'></i></a>
                                                @if($payment->status === 'pending')
                                                    <a href="javascript:;" onclick="updatePaymentStatus({{ $payment->id }}, 'completed')" class="ms-3 text-success" title="Mark as Completed"><i class='bx bx-check'></i></a>
                                                    <a href="javascript:;" onclick="updatePaymentStatus({{ $payment->id }}, 'failed')" class="ms-3 text-danger" title="Mark as Failed"><i class='bx bx-x'></i></a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bx bx-credit-card" style="font-size: 3rem; color: #ddd;"></i>
                                                <h5 class="mt-2">No Payments Found</h5>
                                                <p class="text-muted">No payment records have been found yet.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($payments->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $payments->links("pagination::bootstrap-4") }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
    <!--end page wrapper -->

@endsection

@section('scripts')
<script>
    function viewPayment(paymentId) {
        // Can add modal to view payment details
        alert('View payment details for payment ID: ' + paymentId);
    }

    function updatePaymentStatus(paymentId, status) {
        if(confirm('Are you sure you want to change the status of this payment?')) {
            // Can add AJAX request to update payment status
            alert('Payment status updated to: ' + status);
        }
    }
</script>
@endsection
