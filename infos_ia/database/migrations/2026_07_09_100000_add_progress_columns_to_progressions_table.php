<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('progressions', function (Blueprint $table) {
            $table->timestamp('derniere_activite')->nullable()->after('statut');
            $table->integer('temps_passe')->default(0)->after('derniere_activite');
        });
    }

    public function down(): void
    {
        Schema::table('progressions', function (Blueprint $table) {
            $table->dropColumn(['derniere_activite', 'temps_passe']);
        });
    }
};