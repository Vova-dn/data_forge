<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('import_errors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('row_id')->nullable()->constrained('import_rows');
            $table->foreignId('import_id')->nullable()->constrained('imports');
            $table->timestamps();
            $table->string('field');
            $table->integer('code');
            $table->string('message');
            $table->string('bounded_safe_excerpt');
        });

        DB::statement('
            ALTER TABLE import_errors
            ADD CONSTRAINT import_errors_row_or_import_check
            CHECK (
                (row_id IS NOT NULL AND import_id IS NULL)
                OR
                (row_id IS NULL AND import_id IS NOT NULL)
            )
        ');

    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_errors');
    }
};
