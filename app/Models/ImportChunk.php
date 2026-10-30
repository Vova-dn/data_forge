<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportChunk extends Model
{
    public function importRun(): BelongsTo
    {
        return $this->belongsTo(ImportRun::class);
    }

    public $timestamps = false;


    protected $fillable = [
        'run_id',
        'phase',
        'chunk_index',
        'state',
        'attempts',
        'summary',
    ];

    protected function casts(): array
    {
        return [
            'summary' => 'array',
        ];
    }
}
