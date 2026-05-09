<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration : Ajoute les colonnes de bonus aux premiers résolveurs d'un challenge.
 * bonus_percent  : pourcentage de bonus (25, 15, 5, ou 0)
 * bonus_points   : points bonus réels calculés (base_points * percent / 100)
 * total_points   : total attribué = points + bonus_points
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ctf_scores', function (Blueprint $table) {
            $table->unsignedTinyInteger('bonus_percent')->default(0)->after('points');
            $table->integer('bonus_points')->default(0)->after('bonus_percent');
            $table->integer('total_points')->storedAs('points + bonus_points')->after('bonus_points');
        });
    }

    public function down(): void
    {
        Schema::table('ctf_scores', function (Blueprint $table) {
            $table->dropColumn(['bonus_percent', 'bonus_points', 'total_points']);
        });
    }
};
