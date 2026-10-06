<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class Dataset extends Model
{
    use HasFactory, Notifiable;

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function import(): HasOne
    {
        return $this->hasOne(Import::class);
    }

    public function importRuns(): HasMany
    {
        return $this->hasMany(Import_Run::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function exports(): HasMany
    {
        return $this->hasMany(Export::class);
    }
}
