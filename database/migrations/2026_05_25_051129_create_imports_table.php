<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imports', function (Blueprint $table) {

            $table->id();

            $table->foreignId('sorteo_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('file_path');

            $table->integer('rows_total')
                ->default(0);

            $table->integer('rows_success')
                ->default(0);

            $table->integer('rows_failed')
                ->default(0);

            $table->text('errors')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};