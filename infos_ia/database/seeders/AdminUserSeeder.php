<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Niveau;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Crée un utilisateur administrateur par défaut
     */
    public function run(): void
    {
        $admin = User::where('email', 'yssfmoussa@gmail.com')->first();

        if (!$admin) {
            User::create([
                'username' => 'Moussa Youssouf',
                'email' => 'yssfmoussa@gmail.com',
                'password' => Hash::make('admin123'),
                'statut' => 'actif',
                'role' => 'admin',
                'niveau_id' => null, // L'admin n'a pas de niveau
            ]);

            $this->command->info('✅ Compte admin créé : yssfmoussa@gmail.com / admin123');
        } else {
            // Compte déjà existant : s'assurer que le rôle admin est bien attribué
            if ($admin->role !== 'admin') {
                $admin->update(['role' => 'admin']);
                $this->command->info('✅ Rôle admin mis à jour pour le compte existant.');
            } else {
                $this->command->info('ℹ️ Le compte admin existe déjà.');
            }
        }
    }
}