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
        Schema::create('exports', function (Blueprint $table) {
            $table->id();
            $table->string('kind');
            $table->foreignId('owner_id')->constrained('users');
            $table->foreignId('dataset_id')->nullable()->constrained('datasets');
            $table->foreignId('import_id')->nullable()->constrained('imports');
            $table->string('state');
            $table->string('source_system');
            $table->timestamp('snapshot_at');
            $table->integer('max_customer_id');
            $table->string('object_key');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exports');
    }
};
