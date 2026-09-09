<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('cedula_regente_archivo')->nullable();
            $table->string('titulo_farmaceutico_archivo')->nullable();
            $table->string('ultima_relacion_psicotropica_archivo')->nullable();
            $table->string('carta_solicitud_archivo')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn([
                'cedula_regente_archivo',
                'titulo_farmaceutico_archivo',
                'ultima_relacion_psicotropica_archivo',
                'carta_solicitud_archivo',
            ]);
        });
    }
};
