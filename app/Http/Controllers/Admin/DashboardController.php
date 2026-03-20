<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats hoy
        $today = now()->toDateString();

        $salesToday = Order::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('total');

        $saleYesterday = Order::whereDate('created_at', now()->subDay()->toDateString())
            ->where('payment_status', 'paid')
            ->sum('total');

        $saleDelta = $saleYesterday > 0
            ? round((($salesToday - $saleYesterday) / $saleYesterday) * 100, 1)
            : null;

        $ordersToday   = Order::whereDate('created_at', $today)->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalCustomers = User::where('role', 'customer')->count();

        // Gráfico últimos 6 meses
        $salesChart = Order::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw('SUM(total) as total')
        )
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $chartLabels = [];
        $chartData   = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('Y-m');
            $label = now()->subMonths($i)->locale('es')->isoFormat('MMM YY');
            $chartLabels[] = $label;
            $chartData[]   = (float) ($salesChart[$month]->total ?? 0);
        }

        // Pedidos recientes
        $recentOrders = Order::with('items')
            ->latest()
            ->take(10)
            ->get();

        // Top 5 productos
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->with('product.images')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'salesToday',
            'saleDelta',
            'ordersToday',
            'pendingOrders',
            'totalCustomers',
            'chartLabels',
            'chartData',
            'recentOrders',
            'topProducts',
        ));
    }
}
