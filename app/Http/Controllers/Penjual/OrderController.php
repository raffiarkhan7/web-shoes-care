<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Auth::user()->sellerOrders()->with(['user', 'service']);

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('penjual.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $this->authorizeOrder($order);
        $order->load(['user', 'service']);

        return view('penjual.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $this->authorizeOrder($order);

        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Order::STATUSES))],
            'payment_status' => ['required', Rule::in(array_keys(Order::PAYMENT_STATUSES))],
        ]);

        $order->update($data);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    private function authorizeOrder(Order $order): void
    {
        abort_unless($order->penjual_id === Auth::id(), 403, 'Pesanan ini bukan milik toko Anda.');
    }
}
