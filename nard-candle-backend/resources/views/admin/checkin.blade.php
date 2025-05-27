@extends('admin.layout')

@section('title', 'Order Check-in - Nard Candles Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800">📦 Order Check-in</h1>
            <p class="text-muted">Manage and review all customer orders placed in the system.</p>
        </div>
        <div class="btn-group">
            <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                <i class="fas fa-print"></i> Print
            </button>
            <button class="btn btn-outline-success btn-sm" onclick="exportToCSV()">
                <i class="fas fa-file-csv"></i> Export CSV
            </button>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">🗂 All Orders</h6>
            <small class="text-light">Total: {{ count($orders) }} orders</small>
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
                        @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->tx_ref }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->customer_email }}</td>
                                <td>{{ $order->customer_phone }}</td>
                                <td>
                                    @if(is_array($order->order_items))
                                        @foreach($order->order_items as $item)
                                            <div class="small">
                                                {{ $item['name'] ?? 'Product' }} <span class="badge badge-light">x{{ $item['quantity'] ?? 1 }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        Product ID: {{ $order->product_id ?? 'N/A' }} (x{{ $order->quantity ?? 1 }})
                                    @endif
                                </td>
                                <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                                <td>
                                    <span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary view-order" data-toggle="modal" data-target="#orderDetailModal" data-order-id="{{ $order->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($order->status === 'pending')
                                            <button class="btn btn-outline-success mark-completed" data-order-id="{{ $order->id }}">
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
                                <td colspan="10" class="text-center text-muted">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Order Detail Modal -->
<div class="modal fade" id="orderDetailModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="orderDetailModalLabel">🧾 Order Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="orderDetailContent">
                Loading...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="printOrderDetail">Print</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
@push('scripts')
<script>
    $(document).ready(function () {
        $('#ordersTable').DataTable({
            order: [[8, 'desc']]
        });

        $('.view-order').on('click', function () {
            const orderId = $(this).data('order-id');
            $('#orderDetailContent').html('Loading...');

            $.ajax({
                url: `/api/orders/${orderId}`,
                method: 'GET',
                success: function (order) {
                    let itemsHtml = '';
                    if (Array.isArray(order.order_items)) {
                        order.order_items.forEach(item => {
                            itemsHtml += `
                                <tr>
                                    <td>${item.name || 'N/A'}</td>
                                    <td>${item.quantity || 1}</td>
                                    <td>$${(item.price || 0).toFixed(2)}</td>
                                    <td>$${((item.price || 0) * (item.quantity || 1)).toFixed(2)}</td>
                                </tr>
                            `;
                        });
                    } else {
                        itemsHtml = `
                            <tr>
                                <td>Product ID: ${order.product_id}</td>
                                <td>${order.quantity}</td>
                                <td>$${(order.total_amount / (order.quantity || 1)).toFixed(2)}</td>
                                <td>$${order.total_amount.toFixed(2)}</td>
                            </tr>
                        `;
                    }

                    const html = `
                        <div class="container-fluid">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6>Order Info</h6>
                                    <p><strong>ID:</strong> #${order.id}</p>
                                    <p><strong>Tx Ref:</strong> ${order.tx_ref}</p>
                                    <p><strong>Date:</strong> ${new Date(order.created_at).toLocaleString()}</p>
                                    <p><strong>Status:</strong> 
                                        <span class="badge badge-${order.status === 'completed' ? 'success' : order.status === 'pending' ? 'warning' : 'secondary'}">
                                            ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Customer Info</h6>
                                    <p><strong>Name:</strong> ${order.customer_name}</p>
                                    <p><strong>Email:</strong> ${order.customer_email}</p>
                                    <p><strong>Phone:</strong> ${order.customer_phone || 'N/A'}</p>
                                    <p><strong>Address:</strong> ${order.shipping_address || 'N/A'}</p>
                                </div>
                            </div>
                            <div>
                                <h6>Items</h6>
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>${itemsHtml}</tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-right font-weight-bold">Total:</td>
                                            <td class="font-weight-bold">$${order.total_amount.toFixed(2)}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            ${order.notes ? `<div class="mt-3"><h6>Notes</h6><p>${order.notes}</p></div>` : ''}
                        </div>
                    `;

                    $('#orderDetailContent').html(html);
                },
                error: function () {
                    $('#orderDetailContent').html('<div class="alert alert-danger">Unable to load order details.</div>');
                }
            });
        });
    });

    function exportToCSV() {
        // Stub for CSV export logic
        alert('CSV export not implemented yet.');
    }

    function printOrder(orderId) {
        window.open(`/admin/orders/print/${orderId}`, '_blank');
    }
</script>
@endpush
@endsection
