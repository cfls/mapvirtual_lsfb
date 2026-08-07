<?php

namespace Database\Seeders;

use App\Models\Landmark;
use Illuminate\Database\Seeder;

class LandmarkProvinceSeeder extends Seeder
{
    /**
     * Only updates the `province` column on existing rows (matched by
     * slug) — does not touch name, description, video, coordinates, or
     * anything else already loaded in the database.
     */
    public function run(): void
    {
        $provinces = [
                'grand-place-bruxelles'   => 'Bruxelles-Capitale',
                'beffroi-de-bruges'       => 'Flandre occidentale',
                'chateau-des-comtes-gand' => 'Flandre orientale',
                'cathedrale-anvers'       => 'Anvers',
                'waterloo'                => 'Brabant wallon',
                'beffroi-mons'            => 'Hainaut',
                'cathedrale-tournai'      => 'Hainaut',
                'citadelle-dinant'        => 'Namur',
                'liege'                   => 'Liège',
        ];

        foreach ($provinces as $slug => $province) {
            $updated = Landmark::where('slug', $slug)->update(['province' => $province]);

            if ($updated === 0) {
                $this->command?->warn("Aucun landmark trouve pour le slug \"{$slug}\" — verifie qu'il existe en base.");
            }
        }

        $this->command?->info('Provinces mises a jour pour '.count($provinces).' lieux.');
    }
}