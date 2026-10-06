<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Import_Run extends Model
{
    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }

    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class);
    }

    public function chunks(): HasMany
    {
        return $this->hasMany(Import_Chunk::class);
    }

}
