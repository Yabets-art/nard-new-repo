<!-- resources/views/emails/order-production-completed.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Production Completed</title>
</head>
<body>
    <p>Dear {{ $order->name }},</p>

    <p>Good news! Your custom candle order has been successfully completed, and it’s ready for you.</p>

    <p>You can contact us to arrange the pickup or delivery. If you need any more information, feel free to reach out.</p>

    <p>Thank you once again for choosing <strong>Nard Candles</strong>. We hope your new candle brightens up your space!</p>

    <p>Best regards,</p>
    <p>Nard Candles Team</p>
</body>
</html>
