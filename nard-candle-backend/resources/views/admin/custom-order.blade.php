@extends('admin.layout')

@section('content')
    <div class="container mt-5">
        <h2>Custom Orders</h2>

        @if ($customOrders->isEmpty())
            <p class="alert alert-warning">No custom orders found.</p>
        @else
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Customer Name</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customOrders as $index => $order)
                        <tr class="clickable-row" data-toggle="modal" data-target="#orderModal{{ $order->id }}">
                            <td>{{ ($customOrders->currentPage() - 1) * $customOrders->perPage() + $index + 1 }}</td>
                            <td>{{ $order->name }}</td>
                            <td>{{ $order->contact_method }}</td>
                            <td>{{ $order->status }}</td>
                            <td>
                                <a href="{{ route('custom-orders.accept', $order->id) }}" class="btn btn-success btn-sm">Accept</a>
                                <a href="{{ route('custom-orders.complete', $order->id) }}" class="btn btn-primary btn-sm">Completed</a>
                            </td>
                        </tr>

                        <!-- Modal for extra information -->
                        <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel{{ $order->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="orderModalLabel{{ $order->id }}">Order Details</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Order Preference:</strong> {{ $order->preferences }}</p>
                                        @if($order->image)
                                            <p><strong>Order Image:</strong></p>
                                            <img src="{{ asset('storage/' . $order->image) }}" alt="Order Image" class="img-fluid">
                                        @else
                                            <p><strong>Order Image:</strong> No image available</p>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>

             <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        <ul class="pagination">
            <li class="page-item {{ $customOrders->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $customOrders->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            @for ($i = 1; $i <= $customOrders->lastPage(); $i++)
                <li class="page-item {{ $customOrders->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $customOrders->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            <li class="page-item {{ $customOrders->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $customOrders->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </div>
        @endif
    </div>

    <!-- Add Bootstrap and jQuery -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .clickable-row {
            cursor: pointer;
        }
    </style>
@endsection
