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
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dataset_id')->constrained('datasets');
            $table->timestamps();
            $table->string('source_system');
            $table->string('schema_version');
            $table->string('filename');
            $table->string('size');
            $table->char('checksum', 64);
            $table->string('immutable_source_key');
            $table->string('state');

            $table->index(
                ['dataset_id', 'created_at', 'id'],
                'dataset_import_id'
            );

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};
