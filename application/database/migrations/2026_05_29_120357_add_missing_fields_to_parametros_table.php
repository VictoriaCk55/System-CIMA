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
        Schema::table('parametros', function (Blueprint $table) {
            $table->string('codigo_poe')->nullable()->after('metodo');
            $table->string('limite_cuantificacion')->nullable()->after('codigo_poe');
            $table->string('unidad')->nullable()->after('limite_cuantificacion');
            $table->string('matriz')->nullable()->after('unidad');
            $table->string('tecnica')->nullable()->after('matriz');
        });
    }

    public function down(): void
    {
        Schema::table('parametros', function (Blueprint $table) {
            $table->dropColumn(['codigo_poe', 'limite_cuantificacion', 'unidad', 'matriz', 'tecnica']);
        });
    }
};
