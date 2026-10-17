<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation #{{ \$order->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-green-600">Thank You For Your Order!</h1>
                <p class="text-gray-600">Your order has been placed successfully.</p>
            </div>

            <div class="border-t border-b border-gray-200 py-4 mb-4">
                <div class="flex justify-between mb-2">
                    <span class="font-semibold">Order ID:</span>
                    <span>#{{ \$order->id }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="font-semibold">Date:</span>
                    <span>{{ \$order->created_at->format('Y-m-d H:i') }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="font-semibold">Payment Status:</span>
                    <span class="uppercase text-green-600 font-bold">{{ \$order->payment_status }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold">Order Status:</span>
                    <span class="uppercase text-blue-600 font-bold">{{ \$order->status }}</span>
                </div>
            </div>

            <h2 class="text-xl font-bold mb-3">Customer Information</h2>
            <p><strong>Name:</strong> {{ \$order->customer_name }}</p>
            <p><strong>Email:</strong> {{ \$order->customer_email }}</p>
            <p><strong>Phone:</strong> {{ \$order->customer_phone }}</p>
            <p><strong>Address:</strong> {{ \$order->customer_address }}</p>

            <h2 class="text-xl font-bold mt-6 mb-3">Order Items</h2>
            <table class="w-full text-left border-collapse mb-4">
                <thead>
                    <tr class="border-b">
                        <th class="py-2">Product</th>
                        <th class="py-2">Qty</th>
                        <th class="py-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\$order->orderItems as \$item)
                    <tr class="border-b">
                        <td class="py-2">{{ \$item->product->name ?? 'Product' }}</td>
                        <td class="py-2">{{ \$item->quantity }}</td>
                        <td class="py-2">\${{ number_format(\$item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="flex justify-between text-xl font-bold mt-4">
                <span>Total:</span>
                <span>\${{ number_format(\$order->total_amount, 2) }}</span>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('products.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</body>
</html>
