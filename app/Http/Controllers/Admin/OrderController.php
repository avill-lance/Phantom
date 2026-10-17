<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of orders with filtering.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('customer')) {
            $customer = $request->customer;
            $query->where(function($q) use ($customer) {
                $q->where('customer_name', 'like', "%{$customer}%")
                  ->orWhere('customer_email', 'like', "%{$customer}%");
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the order status and trigger notifications.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();

        // Trigger notification if status changed and customer email exists
        if ($oldStatus !== $order->status && $order->customer_email) {
            try {
                Mail::raw("Your order #{$order->id} status has been updated to: " . ucfirst($order->status), function ($message) use ($order) {
                    $message->to($order->customer_email)
                            ->subject("Order Update - #{$order->id}");
                });
            } catch (\Exception $e) {
                // Log or ignore mail failure in test/dev
            }
        }

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order status updated successfully.');
    }
}
