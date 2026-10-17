<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shopping Cart - Phantom</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <h1 class="text-xl font-semibold text-gray-900">Phantom Clothing</h1>
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <a href="{{ route('products.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Products</a>
                                <a href="{{ route('cart.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cart
                                    @if (session()->has('cart') && count(session()->get('cart')) > 0)
                                        <span class="ml-1 inline-flex h-5 w-5 items-center justify-center bg-indigo-600 rounded-full text-xs font-medium text-white">{{ count(session()->get('cart')) }}</span>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        @auth
                            <div class="ml-3 hidden md:block">
                                <a href="{{ route('profile.edit') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">{{ auth()->user()->name }}</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <main>
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div class="px-4 py-6 sm:px-0">
                    @if (session()->has('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if (session()->has('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (count($cartItems) === 0)
                        <div class="text-center py-12">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Your cart is empty</h2>
                            <p class="text-gray-600 mb-6">Add some products to your cart to get started.</p>
                            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Browse Products
                            </a>
                        </div>
                    @else
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4">Shopping Cart</h2>
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($cartItems as $item)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $item['product']->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $item['quantity'] }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">${{ number_format($item['subtotal'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" class="px-6 py-4 text-right font-bold">Total</td>
                                            <td class="px-6 py-4 font-bold">${{ number_format($total, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>