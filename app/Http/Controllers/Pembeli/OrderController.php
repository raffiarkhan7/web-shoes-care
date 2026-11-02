<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Auth::user()->orders()->with(['service', 'penjual']);

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('pembeli.orders.index', compact('orders'));
    }

    public function create(Request $request): View
    {
        $services = Service::with('penjual')
            ->where('is_active', true)
            ->orderBy('price')
            ->get();

        $selectedService = $request->filled('service')
            ? $services->firstWhere('id', $request->integer('service'))
            : null;

        return view('pembeli.orders.create', compact('services', 'selectedService'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'service_id' => ['required', Rule::exists('services', 'id')->where('is_active', true)],
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'pickup_address' => ['required', 'string', 'max:500'],
            'pickup_date' => ['required', 'date', 'after_or_equal:today'],
            'pickup_time' => ['required', 'string', 'max:50'],
            'pickup_method' => ['required', Rule::in(array_keys(Order::PICKUP_METHODS))],
            'payment_method' => ['required', Rule::in(array_keys(Order::PAYMENT_METHODS))],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $service = Service::with('penjual')->findOrFail($data['service_id']);

        $order = DB::transaction(function () use ($data, $service) {
            return Order::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'user_id' => Auth::id(),
                'penjual_id' => $service->user_id,
                'service_id' => $service->id,
                'quantity' => $data['quantity'],
                'total_price' => $service->price * $data['quantity'],
                'pickup_address' => $data['pickup_address'],
                'pickup_date' => $data['pickup_date'],
                'pickup_time' => $data['pickup_time'],
                'pickup_method' => $data['pickup_method'],
                'notes' => $data['notes'] ?? null,
                'payment_method' => $data['payment_method'],
                'payment_status' => 'unpaid',
                'status' => Order::STATUS_PENDING,
            ]);
        });

        return redirect()
            ->route('pembeli.orders.show', $order)
            ->with('success', 'Pesanan berhasil dibuat. Penjual akan segera mengonfirmasi pesanan Anda.');
    }

    public function show(Order $order): View
    {
        $this->authorizeOrder($order);
        $order->load(['service', 'penjual']);

        return view('pembeli.orders.show', compact('order'));
    }

    public function cancel(Order $order): RedirectResponse
    {
        $this->authorizeOrder($order);

        if (! $order->isCancellable()) {
            return back()->with('error', 'Pesanan ini tidak dapat dibatalkan lagi.');
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    private function authorizeOrder(Order $order): void
    {
        abort_unless($order->user_id === Auth::id(), 403, 'Pesanan ini bukan milik Anda.');
    }

    private function generateInvoiceNumber(): string
    {
        do {
            $invoice = 'SC-'.now()->format('Ymd').'-'.Str::upper(Str::random(5));
        } while (Order::where('invoice_number', $invoice)->exists());

        return $invoice;
    }
}
