<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('rif_archivo')->nullable()->after('rif');
            $table->string('factura_archivo')->nullable()->after('rif_archivo');
            $table->string('permiso_instalacion_archivo')->nullable()->after('factura_archivo');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['rif_archivo', 'factura_archivo', 'permiso_instalacion_archivo']);
        });
    }
};
