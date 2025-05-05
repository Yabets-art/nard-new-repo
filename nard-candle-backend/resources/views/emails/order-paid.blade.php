<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation - Nard Candles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4a6741;
        }
        .order-details {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .order-items {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Thank You for Your Order!</h1>
        <p>Your payment has been successfully processed.</p>
    </div>

    <p>Dear {{ $order->customer_name }},</p>

    <p>We're excited to confirm that we've received your payment for order <strong>#{{ $order->id }}</strong>. Thank you for shopping with Nard Candles!</p>

    <div class="order-details">
        <h3>Order Summary</h3>
        <p><strong>Order ID:</strong> #{{ $order->id }}</p>
        <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
        <p><strong>Payment Date:</strong> {{ $order->paid_at->format('F j, Y') }}</p>
        <p><strong>Total Amount:</strong> ETB {{ number_format($order->total_amount, 2) }}</p>
        <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
        <p><strong>Transaction Reference:</strong> {{ $order->tx_ref }}</p>
        
        <div class="order-items">
            <h4>Order Items</h4>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->order_items as $item)
                    <tr>
                        <td>{{ $item['product_name'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>ETB {{ number_format($item['price'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <p>We'll be processing your order right away. You can check the status of your order at any time by visiting your account on our website.</p>

    <p>If you have any questions or need further assistance, please don't hesitate to contact us.</p>

    <p>Thank you for choosing Nard Candles!</p>

    <p>Warm regards,<br>
    The Nard Candles Team</p>

    <div class="footer">
        <p>© {{ date('Y') }} Nard Candles. All rights reserved.</p>
    </div>
</body>
</html> 