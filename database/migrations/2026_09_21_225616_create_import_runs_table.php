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
        Schema::create('import_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_id')->constrained('imports');
            $table->foreignId('dataset_id')->constrained('datasets');
            $table->integer('run_no');



            $table->string('state');
            $table->boolean('planning_complete')->default(false);

            $table->unique(['import_id', 'run_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_runs');
    }
};
