<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
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

        // Get registered users count
        $registeredUsersCount = User::count();

        // Get monthly earnings data for the chart (last 12 months)
        $monthlyEarnings = [];
        $labels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $earnings = Order::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->where('status', 'completed')
                ->sum('total_amount');
                
            $monthlyEarnings[] = floatval($earnings);
            $labels[] = $date->format('M Y');
        }

        // Get all products with stock information
        $products = Product::select('id', 'name', 'price', 'stock', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($product) {
                $product->stock = $product->stock ?? 0; // Set default stock to 0 if null
                return $product;
            });

        // Return the admin dashboard view with the data
        return view('admin.index', [
            'monthlyRevenue' => $monthlyRevenue,
            'annualRevenue' => $annualRevenue,
            'completedOrdersCount' => $completedOrdersCount,
            'registeredUsersCount' => $registeredUsersCount,
            'products' => $products,
            'monthlyEarnings' => $monthlyEarnings,
            'monthLabels' => $labels
        ]);
    }
} 