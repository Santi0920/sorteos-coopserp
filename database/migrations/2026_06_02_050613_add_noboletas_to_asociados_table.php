<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asociados', function (Blueprint $table) {

            $table->integer('boletas_por_persona')
                ->default(1)
                ->after('cuenta');

        });
    }

    public function down(): void
    {
        Schema::table('asociados', function (Blueprint $table) {

            $table->dropColumn('boletas_por_persona');

        });
    }
};