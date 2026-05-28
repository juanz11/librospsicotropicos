<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('despacho_items', 'fecha_vencimiento')) {
            Schema::table('despacho_items', function (Blueprint $table) {
                $table->dropColumn('fecha_vencimiento');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('despacho_items', 'fecha_vencimiento')) {
            Schema::table('despacho_items', function (Blueprint $table) {
                $table->date('fecha_vencimiento')->nullable();
            });
        }
    }
};
