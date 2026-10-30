<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImportRow extends Model
{
    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }

    public function importErrors(): HasMany
    {
        return $this->hasMany(ImportError::class);
    }
}
