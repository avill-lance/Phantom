<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <h1 class="text-3xl font-bold mb-6">Checkout</h1>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Checkout Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2" for="customer_name">Full Name</label>
                        <input class="w-full px-3 py-2 border rounded-lg" type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" required>
                        @error('customer_name') <span class="text-red-500 text-sm">{{ \$message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2" for="customer_email">Email Address</label>
                        <input class="w-full px-3 py-2 border rounded-lg" type="email" name="customer_email" id="customer_email" value="{{ old('customer_email') }}" required>
                        @error('customer_email') <span class="text-red-500 text-sm">{{ \$message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2" for="customer_phone">Phone Number</label>
                        <input class="w-full px-3 py-2 border rounded-lg" type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}" required>
                        @error('customer_phone') <span class="text-red-500 text-sm">{{ \$message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2" for="customer_address">Shipping Address</label>
                        <textarea class="w-full px-3 py-2 border rounded-lg" name="customer_address" id="customer_address" rows="3" required>{{ old('customer_address') }}</textarea>
                        @error('customer_address') <span class="text-red-500 text-sm">{{ \$message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2" for="payment_method">Payment Method</label>
                        <select class="w-full px-3 py-2 border rounded-lg" name="payment_method" id="payment_method" required>
                            <option value="test">Test Payment</option>
                            <option value="stripe">Stripe</option>
                            <option value="paypal">PayPal</option>
                        </select>
                        @error('payment_method') <span class="text-red-500 text-sm">{{ \$message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg">
                        Place Order & Pay
                    </button>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-md p-6 h-fit">
                <h2 class="text-2xl font-bold mb-4">Order Summary</h2>
                <div class="border-b pb-4 mb-4">
                    @foreach(\$cartItems as \$item)
                    <div class="flex justify-between mb-2">
                        <span>{{ \$item['product']->name }} (x{{ \$item['quantity'] }})</span>
                        <span>\${{ number_format(\$item['subtotal'], 2) }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="flex justify-between text-xl font-bold">
                    <span>Total:</span>
                    <span>\${{ number_format(\$total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
