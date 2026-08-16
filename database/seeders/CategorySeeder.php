<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $club = \App\Models\Club::first(); // ne treba nikakav argument tu samo me drka
        foreach (['U-9', 'U-11', 'U-15', 'Seniori'] as $i => $name) {
            \App\Models\Category::firstOrCreate(
                ['club_id' => $club->id, 'name' => $name],
                ['sort_order' => $i],
            );
        }
    }
}
