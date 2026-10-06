<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Import extends Model
{
    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class);
    }
    public function importRuns(): HasMany
    {
        return $this->hasMany(Import_Run::class);
    }

    public function importRows(): HasMany
    {
        return $this->hasMany(Import_Row::class);
    }

    public function importErrors(): HasMany
    {
        return $this->hasMany(Import_Error::class);
    }

    public function exports(): HasMany
    {
        return $this->hasMany(Export::class);
    }

}
