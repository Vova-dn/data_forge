<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Import_Error extends Model
{
    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }

    public function importRow(): BelongsTo
    {
        return $this->belongsTo(Import_Row::class);
    }
}
