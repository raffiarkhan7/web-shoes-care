<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingTreatment;
use App\Models\WebsiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LandingTreatmentController extends Controller
{
    public function index(): View
    {
        $websiteContent = WebsiteContent::firstOrCreate([], ['draft' => config('landing.content'), 'catalog_initialized' => false]);
        if (! $websiteContent->catalog_initialized) {
            foreach (config('landing.treatments') as $index => $treatment) {
                LandingTreatment::create([...$treatment, 'sort_order' => $index, 'is_active' => true]);
            }
            $websiteContent->catalog_initialized = true;
        }
        $this->syncDraftTreatments($websiteContent);

        return view('admin.website.treatments.index', ['treatments' => LandingTreatment::orderBy('sort_order')->orderBy('id')->get()]);
    }

    public function create(): View
    {
        return view('admin.website.treatments.form', ['treatment' => new LandingTreatment, 'action' => route('admin.website.treatments.store'), 'method' => 'POST']);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']);
        $data['image'] = Storage::disk('public')->url($request->file('image')->store('website/treatments', 'public'));
        $data['problems'] = $this->lines($data['problems']);
        $data['details'] = $this->lines($data['details']);
        $data['sort_order'] ??= LandingTreatment::max('sort_order') + 1;
        $data['is_active'] = $request->boolean('is_active');
        LandingTreatment::create($data);
        $this->syncDraftTreatments(WebsiteContent::firstOrCreate([], ['draft' => config('landing.content')]));

        return redirect()->route('admin.website.treatments.index')->with('success', 'Treatment berhasil ditambahkan.');
    }

    public function edit(LandingTreatment $treatment): View
    {
        return view('admin.website.treatments.form', ['treatment' => $treatment, 'action' => route('admin.website.treatments.update', $treatment), 'method' => 'PUT']);
    }

    public function update(Request $request, LandingTreatment $treatment): RedirectResponse
    {
        $data = $this->validated($request, $treatment);
        $data['problems'] = $this->lines($data['problems']);
        $data['details'] = $this->lines($data['details']);
        $data['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image')) {
            $data['image'] = Storage::disk('public')->url($request->file('image')->store('website/treatments', 'public'));
        } else {
            unset($data['image']);
        }
        $treatment->update($data);
        $this->syncDraftTreatments(WebsiteContent::firstOrCreate([], ['draft' => config('landing.content')]));

        return redirect()->route('admin.website.treatments.index')->with('success', 'Treatment berhasil diperbarui.');
    }

    public function destroy(LandingTreatment $treatment): RedirectResponse
    {
        $treatment->delete();
        $this->syncDraftTreatments(WebsiteContent::firstOrCreate([], ['draft' => config('landing.content')]));

        return back()->with('success', 'Treatment berhasil dihapus dari landing page.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?LandingTreatment $treatment = null): array
    {
        $request->merge(['slug' => $treatment?->slug ?? Str::slug($request->string('name')->toString())]);

        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => [Rule::unique('landing_treatments', 'slug')->ignore($treatment?->id)],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:1500'],
            'problems' => ['required', 'string', 'max:1500'],
            'details' => ['required', 'string', 'max:1500'],
            'price' => ['required', 'integer', 'min:0', 'max:100000000'],
            'image' => [$treatment ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'image_alt' => ['required', 'string', 'max:200'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    /** @return list<string> */
    private function lines(string $value): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', $value) ?: [])));
    }

    private function syncDraftTreatments(WebsiteContent $websiteContent): void
    {
        $draft = array_merge(config('landing.content'), $websiteContent->draft ?? []);
        $draft['treatments'] = LandingTreatment::orderBy('sort_order')->orderBy('id')->get()->toArray();
        $websiteContent->draft = $draft;
        $websiteContent->catalog_initialized = true;
        $websiteContent->save();
    }
}
