<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('clientes', 'sicm')) {
            Schema::table('clientes', function (Blueprint $table) {
                $table->string('sicm')->nullable()->after('rif');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('clientes', 'sicm')) {
            Schema::table('clientes', function (Blueprint $table) {
                $table->dropColumn('sicm');
            });
        }
    }
};
