<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display inventory levels, low stock alerts, and sales reports.
     */
    public function index(Request $request)
    {
        // Inventory & Low Stock Alerts
        $lowStockThreshold = 5;
        $inventoryItems = Inventory::with('product')->get();
        $lowStockItems = $inventoryItems->filter(function ($item) use ($lowStockThreshold) {
            $stock = $item->quantity ?? ($item->product->stock ?? 0);
            return $stock <= $lowStockThreshold;
        });

        // Sales Reports: Daily, Weekly, Monthly Revenue
        $dailyRevenue = Order::where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total_amount');

        $weeklyRevenue = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(7))
            ->sum('total_amount');

        $monthlyRevenue = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(30))
            ->sum('total_amount');

        // Top Selling Products
        $topSellingProducts = OrderItem::select('product_id', DB::raw('sum(quantity) as total_sold'), DB::raw('sum(unit_price * quantity) as total_revenue'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->limit(5)
            ->get();

        return view('admin.reports.index', compact(
            'inventoryItems',
            'lowStockItems',
            'dailyRevenue',
            'weeklyRevenue',
            'monthlyRevenue',
            'topSellingProducts'
        ));
    }

    /**
     * Export reports as CSV.
     */
    public function exportCsv(Request $request)
    {
        $type = $request->get('type', 'sales');

        $filename = "report-{$type}-" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        if ($type === 'inventory') {
            $callback = function () {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Product ID', 'Product Name', 'Quantity', 'Location']);

                $inventory = Inventory::with('product')->get();
                foreach ($inventory as $item) {
                    fputcsv($file, [
                        $item->product_id,
                        $item->product->name ?? 'N/A',
                        $item->quantity,
                        $item->location ?? 'Main'
                    ]);
                }
                fclose($file);
            };
        } else {
            $callback = function () {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Order ID', 'Customer Name', 'Total Amount', 'Payment Status', 'Status', 'Date']);

                $orders = Order::latest()->get();
                foreach ($orders as $order) {
                    fputcsv($file, [
                        $order->id,
                        $order->customer_name,
                        $order->total_amount,
                        $order->payment_status,
                        $order->status,
                        $order->created_at
                    ]);
                }
                fclose($file);
            };
        }

        return response()->stream($callback, 200, $headers);
    }
}
