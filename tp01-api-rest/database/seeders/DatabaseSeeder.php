<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Pour les seeders, je ne trouvais plus les notes de cours qui donnaient le code j'ai donc demandé à ChatGPT: Quel est le code pour inclure des fichiers SQL dans un seeder et les lancer dans le DatabaseSeeder avec Laravel?
        $this->call([
            CategoriesSeeder::class,
            SportsSeeder::class,
            EquipmentSeeder::class,
            EquipmentSportsSeeder::class,
        ]);
    }
}
