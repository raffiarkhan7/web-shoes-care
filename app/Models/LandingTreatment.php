<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['slug', 'category', 'name', 'description', 'problems', 'details', 'price', 'image', 'image_alt', 'sort_order', 'is_active'])]
class LandingTreatment extends Model
{
    protected function casts(): array
    {
        return ['problems' => 'array', 'details' => 'array', 'price' => 'integer', 'sort_order' => 'integer', 'is_active' => 'boolean'];
    }
}
