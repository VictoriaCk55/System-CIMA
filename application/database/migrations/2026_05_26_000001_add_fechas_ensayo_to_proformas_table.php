<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proformas', function (Blueprint $table) {
            $table->date('fecha_inicio_ensayo')->nullable()->after('fecha_emision');
            $table->date('fecha_conclusion_ensayo')->nullable()->after('fecha_inicio_ensayo');
        });
    }

    public function down(): void
    {
        Schema::table('proformas', function (Blueprint $table) {
            $table->dropColumn(['fecha_inicio_ensayo', 'fecha_conclusion_ensayo']);
        });
    }
};
