<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reportes_ambientales', function (Blueprint $table) {
            $table->json('unidad_ruido')->nullable()->after('subtipo_ruido');
        });
    }

    public function down(): void
    {
        Schema::table('reportes_ambientales', function (Blueprint $table) {
            $table->dropColumn('unidad_ruido');
        });
    }
};
