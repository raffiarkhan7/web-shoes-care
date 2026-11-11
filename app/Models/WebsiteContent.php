<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['draft', 'published'])]
class WebsiteContent extends Model
{
    protected function casts(): array
    {
        return ['draft' => 'array', 'published' => 'array'];
    }
}
