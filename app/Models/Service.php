<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'name', 'category', 'description', 'price', 'estimated_days', 'is_active'])]
class Service extends Model
{
    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'estimated_days' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function penjual(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function formattedPrice(): string
    {
        return 'Rp '.number_format($this->price, 0, ',', '.');
    }

    public function categoryLabel(): string
    {
        return $this->category ?: 'Perawatan';
    }
}
