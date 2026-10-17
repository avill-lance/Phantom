<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} - Phantom</title>
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
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="flex flex-col md:flex-row">
                            <!-- Image -->
                            <div class="w-full md:w-1/2 bg-gray-100 flex items-center justify-center">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="max-w-full max-h-full object-contain p-6">
                                @else
                                    <div class="flex items-center justify-center h-64 bg-gray-200 text-gray-500">
                                        No Image
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Details -->
                            <div class="w-full md:w-1/2 p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h2 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h2>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        In Stock
                                    </span>
                                </div>
                                
                                <p class="mb-4 text-gray-600">{{ $product->description }}</p>
                                
                                <div class="flex items-baseline mb-6">
                                    <span class="text-3xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                </div>
                                
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div class="flex items-center">
                                        <label for="quantity" class="block mr-4 text-sm font-medium text-gray-700">Quantity:</label>
                                        <input type="number" id="quantity" name="quantity" min="1" value="1" class="w-20 border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                    
                                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                        Add to Cart
                                    </button>
                                </form>
                                
                                @if($product->stock <= 0)
                                    <p class="mt-4 text-red-600 font-medium">Out of stock</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>