
<!-- resources/views/emails/order-accepted.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Accepted</title>
</head>
<body>
    <p>Dear {{ $order->name }},</p>

    <p>We are excited to inform you that your custom candle order has been accepted! We will begin production soon, and we will notify you once your order is ready.</p>

    <p>If you have any questions or need further assistance, feel free to reach out to us at any time.</p>

    <p>Thank you for choosing <strong>Nard Candles</strong>!</p>

    <p>Best regards,</p>
    <p>Nard Candles Team</p>
</body>
</html>
`