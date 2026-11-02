<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $pembeli = $request->user();

        $services = Service::with('penjual')
            ->where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        $activeOrders = $pembeli->orders()
            ->with(['service', 'penjual'])
            ->whereNotIn('status', [Order::STATUS_COMPLETED, Order::STATUS_CANCELLED])
            ->latest()
            ->get();

        $stats = [
            'active' => $activeOrders->count(),
            'completed' => $pembeli->orders()->where('status', Order::STATUS_COMPLETED)->count(),
            'spent' => $pembeli->orders()->where('payment_status', 'paid')->sum('total_price'),
            'total' => $pembeli->orders()->count(),
        ];

        $history = $pembeli->orders()
            ->with(['service', 'penjual'])
            ->latest()
            ->take(4)
            ->get();

        return view('pembeli.dashboard', compact('services', 'activeOrders', 'stats', 'history'));
    }
}
