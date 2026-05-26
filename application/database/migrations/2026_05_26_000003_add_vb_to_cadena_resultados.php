<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cadena_resultados', function (Blueprint $table) {
            $table->string('vb')->nullable()->after('analizado_por');
        });
    }

    public function down(): void
    {
        Schema::table('cadena_resultados', function (Blueprint $table) {
            $table->dropColumn('vb');
        });
    }
};
