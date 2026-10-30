<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class ImportRun extends Model
{
    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }

    public $timestamps = false;

    protected $fillable = [
        'import_id',
        'dataset_id',
        'run_no',
        'state',
    ];

    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class);
    }

    public function chunks(): HasMany
    {
        return $this->hasMany(ImportChunk::class);
    }

}
