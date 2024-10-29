<!-- resources/views/admin/order.blade.php -->
@extends('admin.layout')



@section('content')
    <h1 class="h3 mb-4 text-gray-800">Orders</h1>
    <p>View and manage customer orders.</p>

    <!-- Table of orders -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>#001</td>
                <td>John Doe</td>
                <td>Lavender Candle</td>
                <td>Shipped</td>
                <td><button class="btn btn-primary btn-sm">View</button></td>
            </tr>
            <!-- Additional orders -->
        </tbody>
    </table>
@endsection
