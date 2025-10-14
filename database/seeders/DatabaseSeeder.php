<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuarios de prueba para el ranking
        $users = [
            ['username' => 'BotanicoMaster', 'email' => 'botanico@plantadex.com', 'plants_count' => 156],
            ['username' => 'PlantLover99', 'email' => 'plantlover@plantadex.com', 'plants_count' => 142],
            ['username' => 'GreenThumb', 'email' => 'greenthumb@plantadex.com', 'plants_count' => 128],
            ['username' => 'NatureExplorer', 'email' => 'nature@plantadex.com', 'plants_count' => 115],
            ['username' => 'FloraFinder', 'email' => 'flora@plantadex.com', 'plants_count' => 98],
            ['username' => 'PlantHunter', 'email' => 'hunter@plantadex.com', 'plants_count' => 87],
            ['username' => 'EcoWarrior', 'email' => 'eco@plantadex.com', 'plants_count' => 76],
            ['username' => 'GardenGuru', 'email' => 'garden@plantadex.com', 'plants_count' => 65],
            ['username' => 'LeafCollector', 'email' => 'leaf@plantadex.com', 'plants_count' => 54],
            ['username' => 'PlantWhisperer', 'email' => 'whisper@plantadex.com', 'plants_count' => 43],
        ];

        foreach ($users as $userData) {
            User::create([
                'username' => $userData['username'],
                'email' => $userData['email'],
                'password' => Hash::make('password123'),
                'plants_count' => $userData['plants_count'],
                'registration_date' => now()->subDays(rand(1, 100)),
            ]);
        }
    }
}