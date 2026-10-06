<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Import_Chunk extends Model
{
    public function importRun(): BelongsTo
    {
        return $this->belongsTo(Import_Run::class);
    }
}
