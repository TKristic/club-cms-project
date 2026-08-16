<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Club;
use App\Models\Player;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    public function run(): void
    {
        $clubId = Club::value('id') ?? 1;

        $imena = [
            'Luka', 'Ivan', 'Marko', 'Ante', 'Josip', 'Matej', 'David', 'Filip', 'Karlo', 'Roko',
            'Petar', 'Domagoj', 'Bruno', 'Fran', 'Niko', 'Toma', 'Vito', 'Jakov', 'Leon', 'Borna',
            'Mateo', 'Dino', 'Stjepan', 'Mislav', 'Tin', 'Šime', 'Andrej', 'Gabriel', 'Patrik', 'Emanuel',
        ];

        $prezimena = [
            'Horvat', 'Kovačević', 'Babić', 'Marić', 'Jurić', 'Novak', 'Kovačić', 'Vuković', 'Knežević', 'Marković',
            'Petrović', 'Matić', 'Tomić', 'Pavić', 'Blažević', 'Grgić', 'Perić', 'Radić', 'Šimić', 'Barišić',
            'Vidović', 'Filipović', 'Bošnjak', 'Lovrić', 'Perković', 'Brkić', 'Lukić', 'Kralj', 'Božić', 'Crnić',
        ];

        $pozicije = ['Vratar', 'Branič', 'Vezni', 'Napadač'];

        // dobni raspon (godina rođenja) po nazivu kategorije
        $godinePoKategoriji = [
            'U-9'     => [2016, 2017],
            'U-11'    => [2014, 2015],
            'U-15'    => [2010, 2011],
            'Seniori' => [1995, 2005],
        ];

        $categories = Category::where('club_id', $clubId)->get();

        if ($categories->isEmpty()) {
            $this->command->warn('Nema kategorija — prvo pokreni CategorySeeder.');
            return;
        }

        $broj = 1;
        $perCategory = 25; // 4 × 25 = 100

        foreach ($categories as $category) {
            $raspon = $godinePoKategoriji[$category->name] ?? [2000, 2010];

            for ($i = 0; $i < $perCategory; $i++) {
                $godina = rand($raspon[0], $raspon[1]);
                $mjesec = rand(1, 12);
                $dan    = rand(1, 28);

                Player::create([
                    'club_id'       => $clubId,
                    'category_id'   => $category->id,
                    'first_name'    => $imena[array_rand($imena)],
                    'last_name'     => $prezimena[array_rand($prezimena)],
                    'position'      => $pozicije[array_rand($pozicije)],
                    'birth_date'    => sprintf('%04d-%02d-%02d', $godina, $mjesec, $dan),
                    'jersey_number' => $broj > 99 ? rand(1, 99) : $broj,
                ]);

                $broj++;
            }
        }

        $this->command->info('Kreirano ' . Player::count() . ' igrača.');
    }
}