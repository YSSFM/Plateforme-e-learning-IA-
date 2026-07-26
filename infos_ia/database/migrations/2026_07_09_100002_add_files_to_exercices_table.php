<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exercices', function (Blueprint $table) {
            $table->string('fichier_enonce', 255)->nullable()->after('enonce');
            $table->string('fichier_correction', 255)->nullable()->after('correction');
        });

        // L'énoncé texte devient optionnel puisqu'un fichier peut désormais le remplacer
        Schema::table('exercices', function (Blueprint $table) {
            $table->text('enonce')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('exercices', function (Blueprint $table) {
            $table->dropColumn(['fichier_enonce', 'fichier_correction']);
            $table->text('enonce')->nullable(false)->change();
        });
    }
};