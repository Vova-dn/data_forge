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
        Schema::create('import_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_id')->constrained('imports');
            $table->integer('row_number');
            $table->string('chunk');
            $table->string('normalized_payload');
            $table->string('business_key');
            $table->char('source_hash');
            $table->integer('candidate_row_id');
            $table->string('outcome');
            $table->timestamps();

            $table->unique(['import_id', 'row_number']);

            $table->index(
                ['import_id', 'outcome', 'row_number'],
                'import_rows_outcome'
            );
            $table->index(
                ['import_id', 'business_key', 'source_hash', 'row_number'],
                'import_duplicates'
            );

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_rows');
    }
};
