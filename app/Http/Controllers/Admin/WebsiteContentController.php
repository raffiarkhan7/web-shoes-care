<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class WebsiteContentController extends Controller
{
    public function edit(): View
    {
        $websiteContent = WebsiteContent::first();

        return view('admin.website.edit', [
            'content' => $websiteContent?->draft ?? config('landing.content'),
            'isPublished' => $websiteContent?->published !== null,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hero_eyebrow' => ['required', 'string', 'max:120'],
            'hero_title' => ['required', 'string', 'max:240'],
            'hero_description' => ['required', 'string', 'max:1000'],
            'hero_proof' => ['required', 'string', 'max:240'],
            'cta_title' => ['required', 'string', 'max:240'],
            'footer_description' => ['required', 'string', 'max:500'],
            'contact_label' => ['required', 'string', 'max:80'],
            'contact_note' => ['required', 'string', 'max:240'],
            'whatsapp_number' => ['nullable', 'regex:/^\+?[0-9]{8,15}$/'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ]);

        $websiteContent = WebsiteContent::firstOrCreate([], [
            'draft' => config('landing.content'),
            'published' => null,
        ]);
        $draft = array_intersect_key($validated, array_flip(array_keys(config('landing.content'))));
        $draft = array_merge(config('landing.content'), $websiteContent->draft ?? [], $draft);

        foreach (['logo', 'hero_image'] as $imageField) {
            if ($request->hasFile($imageField)) {
                $draft[$imageField] = Storage::disk('public')->url($request->file($imageField)->store('website', 'public'));
            }
        }

        $websiteContent->draft = $draft;
        if ($request->boolean('publish')) {
            $websiteContent->published = $draft;
        }
        $websiteContent->save();

        return redirect()->route('admin.website.edit')->with(
            'success',
            $request->boolean('publish')
                ? 'Perubahan berhasil dipublikasikan ke landing page.'
                : 'Perubahan website tersimpan sebagai draft.'
        );
    }

    public function discard(): RedirectResponse
    {
        $websiteContent = WebsiteContent::first();
        if ($websiteContent) {
            $websiteContent->draft = $websiteContent->published ?? config('landing.content');
            $websiteContent->save();
        }

        return redirect()->route('admin.website.edit')->with('success', 'Draft dibatalkan dan dikembalikan ke konten yang dipublikasikan.');
    }
}
