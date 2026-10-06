<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('outbox_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_id');
            $table->string('bounded_payload');
            $table->timestamp('available_at');
            $table->integer('attempts');
            $table->timestamp('dispatched_at');

            $table->unique(['event_id']);

            $table->index(
                ['available_at'],
                'outbox_available_at_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outbox_events');
    }
};
