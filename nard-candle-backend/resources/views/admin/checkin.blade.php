@extends('admin.layout')

@section('title', 'Order Checkin - Nard Candles Admin')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Order Checkin</h1>
        <p class="mb-4">View and manage all customer orders in the system.</p>

        <!-- Orders Table Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">All Orders</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Order Actions:</div>
                        <a class="dropdown-item" href="#" onclick="window.print()"><i class="fas fa-print fa-sm fa-fw mr-2 text-gray-400"></i>Print List</a>
                        <a class="dropdown-item" href="#" onclick="exportToCSV()"><i class="fas fa-download fa-sm fa-fw mr-2 text-gray-400"></i>Export CSV</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="ordersTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Transaction Ref</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Items</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Order Date</th>
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
                                                <div>
                                                    {{ $item['name'] ?? 'Unknown Product' }} 
                                                    (x{{ $item['quantity'] ?? 1 }})
                                                </div>
                                            @endforeach
                                        @else
                                            Product ID: {{ $order->product_id ?? 'N/A' }} (x{{ $order->quantity ?? 1 }})
                                        @endif
                                    </td>
                                    <td>${{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-sm view-order" 
                                                    data-toggle="modal" 
                                                    data-target="#orderDetailModal" 
                                                    data-order-id="{{ $order->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            @if($order->status == 'pending')
                                            <button type="button" class="btn btn-success btn-sm mark-completed"
                                                    data-order-id="{{ $order->id }}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            @endif
                                            
                                            <button type="button" class="btn btn-info btn-sm" onclick="printOrder({{ $order->id }})">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">No orders found</td>
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailModalLabel">Order Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="orderDetailContent">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="printOrderDetail">Print</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for handling orders -->
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#ordersTable').DataTable({
                order: [[8, 'desc']] // Sort by Order Date (column 8) in descending order
            });
            
            // Handle view order button click
            $('.view-order').on('click', function() {
                const orderId = $(this).data('order-id');
                $('#orderDetailContent').html('Loading...');
                
                // Fetch order details
                $.ajax({
                    url: `/api/orders/${orderId}`,
                    method: 'GET',
                    success: function(order) {
                        let itemsHtml = '';
                        
                        if (order.order_items && Array.isArray(order.order_items)) {
                            order.order_items.forEach(item => {
                                itemsHtml += `
                                    <tr>
                                        <td>${item.name || 'Unknown Product'}</td>
                                        <td>${item.quantity || 1}</td>
                                        <td>$${(item.price || 0).toFixed(2)}</td>
                                        <td>$${((item.price || 0) * (item.quantity || 1)).toFixed(2)}</td>
                                    </tr>
                                `;
                            });
                        } else if (order.product_id) {
                            itemsHtml = `
                                <tr>
                                    <td>Product ID: ${order.product_id}</td>
                                    <td>${order.quantity || 1}</td>
                                    <td>$${(order.total_amount / (order.quantity || 1)).toFixed(2)}</td>
                                    <td>$${order.total_amount.toFixed(2)}</td>
                                </tr>
                            `;
                        }
                        
                        const html = `
                            <div class="order-detail-container">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h6>Order Information</h6>
                                        <p><strong>Order ID:</strong> #${order.id}</p>
                                        <p><strong>Transaction Ref:</strong> ${order.tx_ref}</p>
                                        <p><strong>Date:</strong> ${new Date(order.created_at).toLocaleString()}</p>
                                        <p><strong>Status:</strong> <span class="badge badge-${order.status == 'completed' ? 'success' : (order.status == 'pending' ? 'warning' : 'secondary')}">${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Customer Information</h6>
                                        <p><strong>Name:</strong> ${order.customer_name}</p>
                                        <p><strong>Email:</strong> ${order.customer_email}</p>
                                        <p><strong>Phone:</strong> ${order.customer_phone || 'N/A'}</p>
                                        <p><strong>Shipping Address:</strong> ${order.shipping_address || 'N/A'}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <h6>Order Items</h6>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${itemsHtml}
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-right"><strong>Total Amount:</strong></td>
                                                    <td><strong>$${order.total_amount.toFixed(2)}</strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                ${order.notes ? `
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h6>Notes:</h6>
                                        <p>${order.notes}</p>
                                    </div>
                                </div>` : ''}
                            </div>
                        `;
                        
                        $('#orderDetailContent').html(html);
                    },
                    error: function() {
                        $('#orderDetailContent').html('<div class="alert alert-danger">Failed to load order details</div>');
                    }
                });
            });
            
            // Handle mark as completed button click
            $('.mark-completed').on('click', function() {
                const orderId = $(this).data('order-id');
                if (confirm('Mark this order as completed?')) {
                    $.ajax({
                        url: `/api/orders/${orderId}/complete`,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function() {
                            alert('Order marked as completed!');
                            location.reload();
                        },
                        error: function() {
                            alert('Failed to update order status. Please try again.');
                        }
                    });
                }
            });
            
            // Print order details
            $('#printOrderDetail').on('click', function() {
                const printContents = document.getElementById('orderDetailContent').innerHTML;
                const originalContents = document.body.innerHTML;
                
                document.body.innerHTML = `
                    <div style="padding: 20px;">
                        <h1 style="text-align: center;">Nard Candles - Order Details</h1>
                        ${printContents}
                    </div>
                `;
                
                window.print();
                document.body.innerHTML = originalContents;
                location.reload();
            });
        });
        
        // Function to print specific order
        function printOrder(orderId) {
            // Fetch the order and print its details
            $.ajax({
                url: `/api/orders/${orderId}`,
                method: 'GET',
                success: function(order) {
                    let itemsHtml = '';
                    
                    if (order.order_items && Array.isArray(order.order_items)) {
                        order.order_items.forEach(item => {
                            itemsHtml += `
                                <tr>
                                    <td>${item.name || 'Unknown Product'}</td>
                                    <td>${item.quantity || 1}</td>
                                    <td>$${(item.price || 0).toFixed(2)}</td>
                                    <td>$${((item.price || 0) * (item.quantity || 1)).toFixed(2)}</td>
                                </tr>
                            `;
                        });
                    } else if (order.product_id) {
                        itemsHtml = `
                            <tr>
                                <td>Product ID: ${order.product_id}</td>
                                <td>${order.quantity || 1}</td>
                                <td>$${(order.total_amount / (order.quantity || 1)).toFixed(2)}</td>
                                <td>$${order.total_amount.toFixed(2)}</td>
                            </tr>
                        `;
                    }
                    
                    const printContent = `
                        <div style="padding: 20px;">
                            <h1 style="text-align: center;">Nard Candles - Order Receipt</h1>
                            <div style="margin-bottom: 20px;">
                                <div style="float: left; width: 50%;">
                                    <h2>Order Information</h2>
                                    <p><strong>Order ID:</strong> #${order.id}</p>
                                    <p><strong>Transaction Ref:</strong> ${order.tx_ref}</p>
                                    <p><strong>Date:</strong> ${new Date(order.created_at).toLocaleString()}</p>
                                    <p><strong>Status:</strong> ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</p>
                                </div>
                                <div style="float: right; width: 50%;">
                                    <h2>Customer Information</h2>
                                    <p><strong>Name:</strong> ${order.customer_name}</p>
                                    <p><strong>Email:</strong> ${order.customer_email}</p>
                                    <p><strong>Phone:</strong> ${order.customer_phone || 'N/A'}</p>
                                    <p><strong>Shipping Address:</strong> ${order.shipping_address || 'N/A'}</p>
                                </div>
                                <div style="clear: both;"></div>
                            </div>
                            <h2>Order Items</h2>
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="background-color: #f2f2f2;">
                                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Product</th>
                                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Quantity</th>
                                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Unit Price</th>
                                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${itemsHtml}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" style="border: 1px solid #ddd; padding: 8px; text-align: right;"><strong>Total Amount:</strong></td>
                                        <td style="border: 1px solid #ddd; padding: 8px;"><strong>$${order.total_amount.toFixed(2)}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                            ${order.notes ? `
                            <div style="margin-top: 20px;">
                                <h2>Notes:</h2>
                                <p>${order.notes}</p>
                            </div>` : ''}
                        </div>
                    `;
                    
                    const originalContents = document.body.innerHTML;
                    document.body.innerHTML = printContent;
                    window.print();
                    document.body.innerHTML = originalContents;
                }
            });
        }
        
        // Function to export orders to CSV
        function exportToCSV() {
            const table = document.getElementById('ordersTable');
            let csv = [];
            let rows = table.querySelectorAll('tr');
            
            for (let i = 0; i < rows.length; i++) {
                let row = [], cols = rows[i].querySelectorAll('td, th');
                
                for (let j = 0; j < cols.length; j++) {
                    // Clean the text content to handle commas and quotes
                    let data = cols[j].textContent.replace(/(\r\n|\n|\r)/gm, '').replace(/"/g, '""');
                    // Enclose each field in quotes to handle commas within the data
                    row.push('"' + data + '"');
                }
                
                csv.push(row.join(','));
            }
            
            // Create a CSV file and download it
            let csvFile = new Blob([csv.join('\n')], {type: 'text/csv'});
            let downloadLink = document.createElement('a');
            downloadLink.download = 'nard_candles_orders_' + new Date().toISOString().slice(0,10) + '.csv';
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();
        }
    </script>
@endsection 