<?php

namespace Database\Seeders;

use App\Models\Landmark;
use Illuminate\Database\Seeder;

class LandmarkAccessibilitySeeder extends Seeder
{
    /**
     * Only updates `lsfb_accessible` and `age_range` on existing rows
     * (matched by slug) — replace these example values with the real
     * data for each site (whether it has an LSFB-accessible QR code or
     * on-site interpreter/guide, and its recommended age range).
     */
    public function run(): void
    {
        $data = [
            'grand-place-bruxelles'   => ['lsfb_accessible' => true,  'age_range' => 'Tous ages'],
            'beffroi-de-bruges'       => ['lsfb_accessible' => true,  'age_range' => 'Tous ages'],
            'chateau-des-comtes-gand' => ['lsfb_accessible' => false, 'age_range' => 'Ados (12-17 ans)'],
            'cathedrale-anvers'       => ['lsfb_accessible' => true,  'age_range' => 'Adultes (18+)'],
            'waterloo'                => ['lsfb_accessible' => true,  'age_range' => 'Tous ages'],
            'beffroi-mons'            => ['lsfb_accessible' => false, 'age_range' => 'Enfants (-12 ans)'],
            'cathedrale-tournai'      => ['lsfb_accessible' => false, 'age_range' => 'Tous ages'],
            'citadelle-dinant'        => ['lsfb_accessible' => true,  'age_range' => 'Ados (12-17 ans)'],
            'liege'                   => ['lsfb_accessible' => true,  'age_range' => 'Adultes (18+)'],
        ];

        foreach ($data as $slug => $values) {
            $updated = Landmark::where('slug', $slug)->update($values);

            if ($updated === 0) {
                $this->command?->warn("Aucun landmark trouve pour le slug \"{$slug}\".");
            }
        }

        $this->command?->info('Accessibilite et tranche d\'age mises a jour pour '.count($data).' lieux.');
    }
}