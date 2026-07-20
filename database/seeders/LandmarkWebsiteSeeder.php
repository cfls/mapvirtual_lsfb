<?php

namespace Database\Seeders;

use App\Models\Landmark;
use Illuminate\Database\Seeder;

class LandmarkWebsiteSeeder extends Seeder
{
    /**
     * Only updates `website_url` on existing rows (matched by slug).
     * Replace with the real official site for each landmark if these
     * change or if you add more.
     */
    public function run(): void
    {
        $websites = [
            'grand-place-bruxelles'   => 'https://visit.brussels/fr',
            'beffroi-de-bruges'       => 'https://www.visitbruges.be/',
            'chateau-des-comtes-gand' => 'https://www.gravensteengent.be/',
            'cathedrale-anvers'       => 'https://www.dekathedraal.be/',
            'waterloo'                => 'https://www.waterloo1815.be/',
            'beffroi-mons'            => 'https://www.visitmons.be/',
            'cathedrale-tournai'      => 'https://www.tournai.be/',
            'citadelle-dinant'        => 'https://citadellededinant.be/',
            'liege'                   => 'https://www.visitezliege.be/',
        ];

        foreach ($websites as $slug => $url) {
            $updated = Landmark::where('slug', $slug)->update(['website_url' => $url]);

            if ($updated === 0) {
                $this->command?->warn("Aucun landmark trouve pour le slug \"{$slug}\".");
            }
        }

        $this->command?->info('Sites web mis a jour pour '.count($websites).' lieux.');
    }
}