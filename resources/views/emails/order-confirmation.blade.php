<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Order Confirmation #{{ \$order->id }}</h1>
    <p>Thank you for your order, {{ \$order->customer_name }}!</p>
    <p>Your order has been successfully placed and is currently <strong>{{ \$order->status }}</strong>.</p>
    
    <h3>Order Summary:</h3>
    <ul>
        @foreach(\$order->orderItems as \$item)
            <li>{{ \$item->product->name ?? 'Product' }} (x{{ \$item->quantity }}) - \${{ number_format(\$item->subtotal, 2) }}</li>
        @endforeach
    </ul>
    
    <p><strong>Total Amount:</strong> \${{ number_format(\$order->total_amount, 2) }}</p>
    
    <p>Shipping Address: {{ \$order->customer_address }}</p>
    
    <p>Thank you for shopping with us!</p>
</body>
</html>
