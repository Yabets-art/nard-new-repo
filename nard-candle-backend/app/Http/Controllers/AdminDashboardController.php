<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get monthly revenue (sum of total_amount for current month)
        $monthlyRevenue = Order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('status', 'completed')
            ->sum('total_amount');

        // Get annual revenue (sum of total_amount for current year)
        $annualRevenue = Order::whereYear('created_at', Carbon::now()->year)
            ->where('status', 'completed')
            ->sum('total_amount');

        // Get completed orders count
        $completedOrdersCount = Order::where('status', 'completed')->count();

        // Get pending requests (count of orders with status 'pending')
        $pendingRequestsCount = Order::where('status', 'pending')->count();

        // Get most sold products
        // Since order_items is stored as JSON, we need to decode it if it's not already an array
        $productSales = [];
        $orders = Order::where('status', 'completed')->get();
        
        foreach ($orders as $order) {
            if (!empty($order->order_items)) {
                $items = $order->order_items;
                
                // Check if items is a string (JSON) and decode it
                if (is_string($items)) {
                    $items = json_decode($items, true);
                }
                
                // Make sure $items is an array before iterating
                if (is_array($items)) {
                    foreach ($items as $item) {
                        if (isset($item['product_id']) && isset($item['quantity'])) {
                            $productId = $item['product_id'];
                            $quantity = $item['quantity'];
                            
                            if (!isset($productSales[$productId])) {
                                $productSales[$productId] = 0;
                            }
                            
                            $productSales[$productId] += $quantity;
                        }
                    }
                }
            }
        }
        
        // Sort products by sales
        arsort($productSales);
        
        // Get top 4 products
        $topProducts = [];
        $totalSales = array_sum($productSales);
        
        if ($totalSales > 0) {
            $count = 0;
            foreach ($productSales as $productId => $sales) {
                if ($count >= 4) break;
                
                $product = Product::find($productId);
                if ($product) {
                    $percentage = round(($sales / $totalSales) * 100);
                    $topProducts[] = [
                        'name' => $product->name,
                        'sales' => $sales,
                        'percentage' => $percentage
                    ];
                    $count++;
                }
            }
        }
        
        // If we don't have enough products, add placeholders
        if (count($topProducts) < 4) {
            $placeholders = [
                ['name' => 'Lavender Candle', 'sales' => 0, 'percentage' => 0],
                ['name' => 'Rose Candle', 'sales' => 0, 'percentage' => 0],
                ['name' => 'Vanilla Candle', 'sales' => 0, 'percentage' => 0],
                ['name' => 'Citrus Candle', 'sales' => 0, 'percentage' => 0]
            ];
            
            for ($i = count($topProducts); $i < 4; $i++) {
                $topProducts[] = $placeholders[$i];
            }
        }

        // Return the admin dashboard view with the data
        return view('admin.index', [
            'monthlyRevenue' => $monthlyRevenue,
            'annualRevenue' => $annualRevenue,
            'completedOrdersCount' => $completedOrdersCount,
            'pendingRequestsCount' => $pendingRequestsCount,
            'topProducts' => $topProducts
        ]);
    }
} 