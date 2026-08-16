<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Club::firstOrCreate(['id' => 1], [
            'name' => 'NK Primjer',
            'primary_color' => '#1e3a8a',
            'secondary_color' => '#f59e0b',
            'contact_email' => 'info@nkprimjer.hr',
        ]);
    }
}
