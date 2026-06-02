<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cadena_resultados', function (Blueprint $table) {
            $table->unsignedBigInteger('proforma_id')->nullable();
            $table->foreign('proforma_id')->references('id')->on('proformas')->onDelete('cascade');
        });

        DB::statement('ALTER TABLE cadena_resultados ALTER COLUMN cadena_custodia_id DROP NOT NULL');
    }

    public function down(): void
    {
        Schema::table('cadena_resultados', function (Blueprint $table) {
            $table->dropForeign(['proforma_id']);
            $table->dropColumn('proforma_id');
        });

        DB::statement('ALTER TABLE cadena_resultados ALTER COLUMN cadena_custodia_id SET NOT NULL');
    }
};
