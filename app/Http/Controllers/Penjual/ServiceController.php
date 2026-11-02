<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Auth::user()->services()->withCount('orders')->latest()->paginate(10);

        return view('penjual.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('penjual.services.form', [
            'service' => new Service,
            'action' => route('penjual.services.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Auth::user()->services()->create($this->validated($request));

        return redirect()->route('penjual.services.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service): View
    {
        $this->authorizeOwner($service);

        return view('penjual.services.form', [
            'service' => $service,
            'action' => route('penjual.services.update', $service),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $this->authorizeOwner($service);

        $service->update($this->validated($request));

        return redirect()->route('penjual.services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function toggle(Service $service): RedirectResponse
    {
        $this->authorizeOwner($service);

        $service->update(['is_active' => ! $service->is_active]);

        return back()->with('success', 'Status layanan berhasil diubah.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $this->authorizeOwner($service);

        if ($service->orders()->exists()) {
            return back()->with('error', 'Layanan sudah memiliki pesanan, nonaktifkan saja.');
        }

        $service->delete();

        return back()->with('success', 'Layanan berhasil dihapus.');
    }

    private function authorizeOwner(Service $service): void
    {
        abort_unless($service->user_id === Auth::id(), 403, 'Layanan ini bukan milik Anda.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'integer', 'min:0', 'max:100000000'],
            'estimated_days' => ['required', 'integer', 'min:1', 'max:30'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
