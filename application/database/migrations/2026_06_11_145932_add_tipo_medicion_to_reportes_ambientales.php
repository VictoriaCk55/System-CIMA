<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reportes_ambientales', function (Blueprint $table) {
            $table->string('tipo_medicion', 100)->nullable()->after('tipo_muestreo');
        });
    }

    public function down(): void
    {
        Schema::table('reportes_ambientales', function (Blueprint $table) {
            $table->dropColumn('tipo_medicion');
        });
    }
};
