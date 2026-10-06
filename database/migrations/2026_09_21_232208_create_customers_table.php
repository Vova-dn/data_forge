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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('dataset_id')->constrained('datasets');
            $table->string('source_system');
            $table->integer('external_id');
            $table->char('source_hash');
            $table->string('normalized_fields');
            $table->string('enrichment');
            $table->integer('origin_import_id');

            $table->unique(['dataset_id', 'source_system', 'external_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
