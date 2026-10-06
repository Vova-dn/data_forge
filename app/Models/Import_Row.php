<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Import_Row extends Model
{
    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }

    public function importErrors(): HasMany
    {
        return $this->hasMany(Import_Error::class);
    }
}
