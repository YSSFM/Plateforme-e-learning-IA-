<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Niveau;

class NiveauxTableSeeder extends Seeder
{
    /**
     * Remplit la table niveaux avec S1, S2, S3, S4
     */
    public function run(): void
    {
        $niveaux = [
            ['code' => 'S1', 'libelle' => 'Semestre 1', 'ordre' => 1],
            ['code' => 'S2', 'libelle' => 'Semestre 2', 'ordre' => 2],
            ['code' => 'S3', 'libelle' => 'Semestre 3', 'ordre' => 3],
            ['code' => 'S4', 'libelle' => 'Semestre 4', 'ordre' => 4],
        ];

        foreach ($niveaux as $niveau) {
            Niveau::create($niveau);
        }

        $this->command->info('✅ Niveaux S1, S2, S3, S4 créés avec succès !');
    }
}