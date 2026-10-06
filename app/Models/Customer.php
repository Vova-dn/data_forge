<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class);
    }
}
