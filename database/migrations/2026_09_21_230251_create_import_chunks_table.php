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
        Schema::create('import_chunks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('run_id')->constrained('import_runs');
            $table->string('phase');
            $table->unsignedInteger('chunk_index');
            $table->string('state');
            $table->timestamp('lease_until');
            $table->uuid('lease_token');
            $table->integer('attempts');
            $table->string('summary');

            $table->unique(['run_id', 'phase', 'chunk_index']);

            $table->index(['state', 'lease_until'], 'chunk_state_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_chunks');
    }
};
