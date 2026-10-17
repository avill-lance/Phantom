<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        \Log::debug('Cart from session: ' . print_r($cart, true));

        $cartItems = [];
        $total = 0;

        foreach ($cart as $productId => $item) {
            \Log::debug('Processing productId: ' . $productId . ', item: ' . print_r($item, true));
            $product = Product::find($productId);
            if ($product) {
                \Log::debug('Product found: ' . $product->id);
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $product->price * $item['quantity']
                ];
                $total += $product->price * $item['quantity'];
            } else {
                \Log::debug('Product NOT found for id: ' . $productId);
            }
        }

        \Log::debug('CartItems: ' . print_r($cartItems, true));
        \Log::debug('Total: ' . $total);

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);
        
        // Validate stock availability
        if ($product->stock < $quantity) {
            return back()->with('error', 'Not enough stock available');
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'quantity' => $quantity
            ];
        }

        Session::put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Product added to cart');
    }

    /**
     * Remove a product from the cart.
     */
    public function remove(Product $product)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            Session::put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Product removed from cart');
    }

    /**
     * Update the quantity of a product in the cart.
     */
    public function update(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);
        
        // Validate stock availability
        if ($product->stock < $quantity) {
            return back()->with('error', 'Not enough stock available');
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$product->id])) {
            if ($quantity <= 0) {
                unset($cart[$product->id]);
            } else {
                $cart[$product->id]['quantity'] = $quantity;
            }
            
            Session::put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated');
    }
}
