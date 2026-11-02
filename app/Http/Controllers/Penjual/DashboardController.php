<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $penjual = $request->user();

        $stats = [
            'services' => $penjual->services()->count(),
            'active_services' => $penjual->services()->where('is_active', true)->count(),
            'incoming' => $penjual->sellerOrders()->whereIn('status', [
                Order::STATUS_PENDING,
                Order::STATUS_CONFIRMED,
                Order::STATUS_PICKED_UP,
                Order::STATUS_PROCESSING,
                Order::STATUS_READY,
                Order::STATUS_DELIVERING,
            ])->count(),
            'completed' => $penjual->sellerOrders()->where('status', Order::STATUS_COMPLETED)->count(),
            'revenue' => $penjual->sellerOrders()->where('payment_status', 'paid')->sum('total_price'),
            'pending' => $penjual->sellerOrders()->where('status', Order::STATUS_PENDING)->count(),
        ];

        $recentOrders = $penjual->sellerOrders()
            ->with(['user', 'service'])
            ->latest()
            ->take(6)
            ->get();

        $services = $penjual->services()
            ->withCount('orders')
            ->latest()
            ->take(4)
            ->get();

        return view('penjual.dashboard', compact('stats', 'recentOrders', 'services'));
    }
}
