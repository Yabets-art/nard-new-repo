{{-- resources/views/admin/order-checkin.blade.php --}}
@extends('admin.layout')

@section('title', 'Order Check-in - Nard Candles Admin')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md">
            <h1 class="h3 text-gray-800 mb-0">📦 Order Check-in</h1>
            <p class="text-muted mb-0">Manage and review all customer orders placed in the system.</p>
        </div>
        <div class="col-md-auto">
            <div class="btn-group">
                <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
                <button class="btn btn-outline-success btn-sm" onclick="exportToCSV()">
                    <i class="fas fa-file-csv"></i> Export CSV
                </button>
            </div>
        </div>
    </div>

    <!-- Orders Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold">🗂 All Orders</h6>
            <small>Total: {{ $orders->count() }} orders</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0" id="ordersTable">
                    <thead class="thead-dark">
                        <tr>
                            <th>#ID</th>
                            <th>Tx Ref</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->tx_ref }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->customer_email }}</td>
                                <td>{{ $order->customer_phone }}</td>
                                <td style="min-width: 150px;">
                                    @if(is_array($order->order_items))
                                        @foreach($order->order_items as $item)
                                            <div class="small">
                                                {{ $item['name'] ?? 'Product' }}
                                                <span class="badge badge-light">x{{ $item['quantity'] ?? 1 }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="small">Product ID: {{ $order->product_id ?? 'N/A' }}</div>
                                        <div class="small">Qty: {{ $order->quantity ?? 1 }}</div>
                                    @endif
                                </td>
                                <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                                <td>
                                    @php
                                        $badge = match($order->status) {
                                            'completed' => 'success',
                                            'pending'   => 'warning',
                                            default     => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge badge-{{ $badge }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary view-order" 
                                                data-toggle="modal" 
                                                data-target="#orderDetailModal" 
                                                data-order-id="{{ $order->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($order->status === 'pending')
                                            <button class="btn btn-outline-success mark-completed" 
                                                    data-order-id="{{ $order->id }}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-outline-info" onclick="printOrder({{ $order->id }})">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Order Detail Modal -->
<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="orderDetailModalLabel">🧾 Order Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="orderDetailContent">
                Loading…
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="printOrderDetail">Print</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function(){
    // DataTables init
    $('#ordersTable').DataTable({ order: [[8, 'desc']] });

    // Load order details via AJAX
    $('.view-order').click(function(){
        const id = $(this).data('order-id');
        $('#orderDetailContent').text('Loading…');
        $.getJSON(`/api/orders/${id}`, order => {
            let rows = '';
            if (Array.isArray(order.order_items)) {
                order.order_items.forEach(i => {
                    rows += `<tr>
                        <td>${i.name||'N/A'}</td>
                        <td>${i.quantity||1}</td>
                        <td>$${(i.price||0).toFixed(2)}</td>
                        <td>$${((i.price||0)*(i.quantity||1)).toFixed(2)}</td>
                    </tr>`;
                });
            } else {
                rows = `<tr>
                    <td>Product ID: ${order.product_id}</td>
                    <td>${order.quantity}</td>
                    <td>$${(order.total_amount/(order.quantity||1)).toFixed(2)}</td>
                    <td>$${order.total_amount.toFixed(2)}</td>
                </tr>`;
            }

            const html = `
                <div class="container-fluid">
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <h6>Order Info</h6>
                            <p><strong>ID:</strong> #${order.id}</p>
                            <p><strong>Tx Ref:</strong> ${order.tx_ref}</p>
                            <p><strong>Date:</strong> ${new Date(order.created_at).toLocaleString()}</p>
                            <p><strong>Status:</strong> 
                              <span class="badge badge-${order.status==='completed'?'success':order.status==='pending'?'warning':'secondary'}">
                                ${order.status.charAt(0).toUpperCase()+order.status.slice(1)}
                              </span>
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <h6>Customer Info</h6>
                            <p><strong>Name:</strong> ${order.customer_name}</p>
                            <p><strong>Email:</strong> ${order.customer_email}</p>
                            <p><strong>Phone:</strong> ${order.customer_phone||'N/A'}</p>
                            <p><strong>Address:</strong> ${order.shipping_address||'N/A'}</p>
                        </div>
                    </div>
                    <h6>Items</h6>
                    <table class="table table-sm table-bordered mb-3">
                        <thead><tr>
                            <th>Product</th><th>Qty</th><th>Price</th><th>Total</th>
                        </tr></thead>
                        <tbody>${rows}</tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right font-weight-bold">Total:</td>
                                <td class="font-weight-bold">$${order.total_amount.toFixed(2)}</td>
                            </tr>
                        </tfoot>
                    </table>
                    ${order.notes?`<div><h6>Notes</h6><p>${order.notes}</p></div>`:''}
                </div>`;
            $('#orderDetailContent').html(html);
        }).fail(()=>{
            $('#orderDetailContent').html('<div class="alert alert-danger">Unable to load order details.</div>');
        });
    });
});

// Stub CSV export & print
function exportToCSV(){ alert('CSV export not implemented yet.'); }
function printOrder(id){ window.open(`/admin/orders/print/${id}`, '_blank'); }
</script>
@endpush
@endsection
