<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'pembeli' => User::where('role', User::ROLE_PEMBELI)->count(),
            'penjual' => User::where('role', User::ROLE_PENJUAL)->count(),
            'orders' => Order::count(),
            'pending' => Order::where('status', Order::STATUS_PENDING)->count(),
            'revenue' => Order::where('payment_status', 'paid')->sum('total_price'),
            'services' => Service::count(),
        ];

        $recentOrders = Order::with(['user', 'service', 'penjual'])
            ->latest()
            ->take(6)
            ->get();

        $topServices = Service::withCount('orders')
            ->orderByDesc('orders_count')
            ->take(5)
            ->get();

        $statusBreakdown = Order::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topServices', 'statusBreakdown'));
    }
}
