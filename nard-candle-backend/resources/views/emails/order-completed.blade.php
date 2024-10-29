<!-- resources/views/emails/order-completed.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Completed</title>
</head>
<body>
    <p>Dear {{ $order->name }},</p>

    <p>We are pleased to let you know that your custom candle order is now complete and ready for you! You can pick it up or have it delivered as per your preferences.</p>

    <p>If you have any questions or need further details, please feel free to contact us. We're always happy to assist you!</p>

    <p>Thank you for choosing <strong>Nard Candles</strong>. We hope you enjoy your custom-made candle!</p>

    <p>Best regards,</p>
    <p>Nard Candles Team</p>
</body>
</html>
