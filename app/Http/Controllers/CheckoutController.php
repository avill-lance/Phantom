<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Mail\OrderConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    /**
     * Display the checkout form with cart items.
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartItems = [];
        $total = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $product->price * $item['quantity']
                ];
                $total += $product->price * $item['quantity'];
            }
        }

        return view('checkout.index', compact('cartItems', 'total'));
    }

    /**
     * Process checkout and payment, create order, and send confirmation email.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:50',
            'customer_address' => 'required|string|max:500',
            'payment_method' => 'required|string|in:stripe,paypal,test',
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartItems = [];
        $total = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if (!$product || $product->stock < $item['quantity']) {
                return redirect()->route('cart.index')->with('error', 'Product ' . ($product ? $product->name : 'Unknown') . ' is out of stock or insufficient quantity.');
            }
            $subtotal = $product->price * $item['quantity'];
            $cartItems[] = [
                'product' => $product,
                'quantity' => $item['quantity'],
                'unit_price' => $product->price,
                'subtotal' => $subtotal
            ];
            $total += $subtotal;
        }

        DB::beginTransaction();

        try {
            // Simulate payment processing (Stripe / PayPal / Test)
            // In test mode or simulated webhook, assume payment success
            $paymentStatus = 'paid'; // 'paid' or 'failed'
            $orderStatus = 'processing';

            $order = Order::create([
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'total_amount' => $total,
                'payment_status' => $paymentStatus,
                'payment_method' => $request->payment_method,
                'status' => $orderStatus,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Reduce stock
                $item['product']->decrement('stock', $item['quantity']);
            }

            // Clear cart
            Session::forget('cart');

            // Send Order Confirmation Email (optional / configurable)
            if (config('app.send_order_emails', true)) {
                try {
                    Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
                } catch (\Exception $e) {
                    \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
                }
            }

            DB::commit();

            return redirect()->route('checkout.confirmation', $order->id)->with('success', 'Order placed successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage());
            return redirect()->route('checkout.index')->with('error', 'An error occurred during checkout: ' . $e->getMessage());
        }
    }

    /**
     * Display order confirmation page.
     */
    public function confirmation(Order $order)
    {
        return view('checkout.confirmation', compact('order'));
    }
}
