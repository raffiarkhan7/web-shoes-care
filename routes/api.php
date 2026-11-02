<?php

use App\Models\Service;
use Illuminate\Support\Facades\Route;

Route::get('/services', function () {
    return Service::with('penjual:id,name,shop_name')
        ->where('is_active', true)
        ->orderBy('price')
        ->get()
        ->map(fn (Service $service) => [
            'id' => $service->id,
            'name' => $service->name,
            'category' => $service->categoryLabel(),
            'description' => $service->description,
            'price' => $service->price,
            'formatted_price' => $service->formattedPrice(),
            'estimated_days' => $service->estimated_days,
            'seller' => $service->penjual?->shop_name ?: $service->penjual?->name,
        ]);
});
