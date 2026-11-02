<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Service::with('penjual')->withCount('orders');

        if ($search = $request->string('search')->toString()) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('penjual')) {
            $query->where('user_id', $request->integer('penjual'));
        }

        $services = $query->latest()->paginate(10)->withQueryString();
        $sellers = User::where('role', User::ROLE_PENJUAL)->orderBy('shop_name')->get();

        return view('admin.services.index', compact('services', 'sellers'));
    }

    public function create(): View
    {
        $sellers = User::where('role', User::ROLE_PENJUAL)->orderBy('shop_name')->get();

        return view('admin.services.form', [
            'service' => new Service,
            'sellers' => $sellers,
            'action' => route('admin.services.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Service::create($this->validated($request));

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service): View
    {
        $sellers = User::where('role', User::ROLE_PENJUAL)->orderBy('shop_name')->get();

        return view('admin.services.form', [
            'service' => $service,
            'sellers' => $sellers,
            'action' => route('admin.services.update', $service),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $service->update($this->validated($request));

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function toggle(Service $service): RedirectResponse
    {
        $service->update(['is_active' => ! $service->is_active]);

        return back()->with('success', 'Status layanan berhasil diubah.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return back()->with('success', 'Layanan berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')->where('role', User::ROLE_PENJUAL)],
            'name' => ['required', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'integer', 'min:0', 'max:100000000'],
            'estimated_days' => ['required', 'integer', 'min:1', 'max:30'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
