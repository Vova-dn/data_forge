<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Import extends Model
{
    protected $fillable = [
        'dataset_id',
        'source_system',
        'schema_version',
        'filename',
        'size',
        'checksum',
        'immutable_source_key',
        'state'
    ];
    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class);
    }
    public function importRuns(): HasMany
    {
        return $this->hasMany(ImportRun::class);
    }

    public function importRows(): HasMany
    {
        return $this->hasMany(ImportRow::class);
    }

    public function importErrors(): HasMany
    {
        return $this->hasMany(ImportError::class);
    }

    public function exports(): HasMany
    {
        return $this->hasMany(Export::class);
    }

}
