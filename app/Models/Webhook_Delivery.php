<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Webhook_Delivery extends Model
{
    public function outboxEvent(): BelongsTo
    {
        return $this->belongsTo(Outbox_Event::class);
    }
}
