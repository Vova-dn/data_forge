<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Outbox_Event extends Model
{
    public function webhookDeliveries(): HasMany
    {
        return $this->HasMany(Webhook_Delivery::class);
    }
}
