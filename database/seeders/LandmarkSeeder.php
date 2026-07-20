<?php

namespace Database\Seeders;

use App\Models\Landmark;
use Illuminate\Database\Seeder;

class LandmarkSeeder extends Seeder
{
    /**
     * NOTE ON VIDEOS
     * ------------------------------------------------------------------
     * Each landmark below points at Cloudinary's public "demo" cloud so
     * the prototype plays a real video out of the box. Replace
     * `cloudinary_cloud_name` with your own LSFB Cloudinary account and
     * `cloudinary_public_id` with the id of the real sign-language video
     * for that landmark (e.g. "lsfb/grand-place-bruxelles").
     */
    public function run(): void
    {
        $landmarks = [
            [
                'name' => 'Grand-Place de Bruxelles',
                'slug' => 'grand-place-bruxelles',
                'region' => 'Bruxelles',
                'excerpt' => 'La place la plus theatrale d\'Europe.',
                'description' => 'Classee au patrimoine mondial de l\'UNESCO, la Grand-Place reunit l\'Hotel de Ville gothique et les maisons de guildes baroques. Decouvrez son histoire racontee en LSFB.',
                'image_url' => 'https://images.unsplash.com/photo-1559113202-c916b8e44373?w=800&q=80',
                'cloudinary_public_id' => 'dog',
                'cloudinary_cloud_name' => 'demo',
                'pos_x' => 50.5,
                'pos_y' => 42,
                'latitude' => 50.846700,
                'longitude' => 4.352500,
                'sort_order' => 1,
            ],
            [
                'name' => 'Beffroi de Bruges',
                'slug' => 'beffroi-de-bruges',
                'region' => 'Flandre',
                'excerpt' => 'La tour qui veille sur la Venise du Nord.',
                'description' => 'Ce beffroi medieval de 83 metres domine le Markt de Bruges. Ses 366 marches racontent huit siecles de commerce et de vie urbaine.',
                'image_url' => 'https://images.unsplash.com/photo-1491557345352-5929e343eb89?w=800&q=80',
                'cloudinary_public_id' => 'elephants',
                'cloudinary_cloud_name' => 'demo',
                'pos_x' => 21,
                'pos_y' => 32,
                'latitude' => 51.208900,
                'longitude' => 3.224700,
                'sort_order' => 2,
            ],
            [
                'name' => 'Chateau des Comtes, Gand',
                'slug' => 'chateau-des-comtes-gand',
                'region' => 'Flandre',
                'excerpt' => 'Une forteresse medievale au coeur de la ville.',
                'description' => 'Le Gravensteen fut le siege du pouvoir des comtes de Flandre. Son donjon et ses douves racontent une histoire de pouvoir et de defense.',
                'image_url' => 'https://images.unsplash.com/photo-1591698933907-4cef5e69e5c7?w=800&q=80',
                'cloudinary_public_id' => 'dog',
                'cloudinary_cloud_name' => 'demo',
                'pos_x' => 38,
                'pos_y' => 34,
                'latitude' => 51.057300,
                'longitude' => 3.720800,
                'sort_order' => 3,
            ],
            [
                'name' => 'Cathedrale Notre-Dame, Anvers',
                'slug' => 'cathedrale-anvers',
                'region' => 'Flandre',
                'excerpt' => 'La plus grande cathedrale gothique du Benelux.',
                'description' => 'Abritant des oeuvres de Rubens, cette cathedrale est le coeur spirituel et artistique d\'Anvers depuis le 14e siecle.',
                'image_url' => 'https://images.unsplash.com/photo-1601581875039-e899893d520c?w=800&q=80',
                'cloudinary_public_id' => 'elephants',
                'cloudinary_cloud_name' => 'demo',
                'pos_x' => 59,
                'pos_y' => 28,
                'latitude' => 51.221300,
                'longitude' => 4.400500,
                'sort_order' => 4,
            ],
            [
                'name' => 'Site du Champ de Bataille de Waterloo',
                'slug' => 'waterloo',
                'region' => 'Wallonie',
                'excerpt' => 'L\'endroit qui a change le destin de l\'Europe.',
                'description' => 'Le 18 juin 1815, la bataille de Waterloo mit fin a l\'ere napoleonienne. La Butte du Lion domine encore ce champ historique.',
                'image_url' => 'https://images.unsplash.com/photo-1548013146-72479768bada?w=800&q=80',
                'cloudinary_public_id' => 'dog',
                'cloudinary_cloud_name' => 'demo',
                'pos_x' => 45,
                'pos_y' => 52,
                'latitude' => 50.717600,
                'longitude' => 4.399500,
                'sort_order' => 5,
            ],
            [
                'name' => 'Beffroi et Doudou, Mons',
                'slug' => 'beffroi-mons',
                'region' => 'Wallonie',
                'excerpt' => 'La ville du combat legendaire contre le dragon.',
                'description' => 'Chaque annee, le Ducasse de Mons met en scene le combat de Saint Georges contre le dragon, une tradition inscrite au patrimoine immateriel de l\'UNESCO.',
                'image_url' => 'https://images.unsplash.com/photo-1543429257-3e419e5f83f9?w=800&q=80',
                'cloudinary_public_id' => 'elephants',
                'cloudinary_cloud_name' => 'demo',
                'pos_x' => 29,
                'pos_y' => 60,
                'latitude' => 50.454200,
                'longitude' => 3.956300,
                'sort_order' => 6,
            ],
            [
                'name' => 'Cathedrale de Tournai',
                'slug' => 'cathedrale-tournai',
                'region' => 'Wallonie',
                'excerpt' => 'Cinq clochers qui dominent l\'Escaut.',
                'description' => 'Melange unique de roman et de gothique, la cathedrale Notre-Dame de Tournai est l\'une des plus anciennes de Belgique.',
                'image_url' => 'https://images.unsplash.com/photo-1548013146-72479768bada?w=800&q=80',
                'cloudinary_public_id' => 'dog',
                'cloudinary_cloud_name' => 'demo',
                'pos_x' => 17,
                'pos_y' => 55,
                'latitude' => 50.605300,
                'longitude' => 3.388300,
                'sort_order' => 7,
            ],
            [
                'name' => 'Citadelle de Dinant',
                'slug' => 'citadelle-dinant',
                'region' => 'Wallonie',
                'excerpt' => 'Une forteresse perchee au-dessus de la Meuse.',
                'description' => 'Accessible par 408 marches ou par teleferique, la citadelle offre une vue vertigineuse sur la vallee de la Meuse et la collegiale de Dinant.',
                'image_url' => 'https://images.unsplash.com/photo-1585208798174-6cedd86e019a?w=800&q=80',
                'cloudinary_public_id' => 'elephants',
                'cloudinary_cloud_name' => 'demo',
                'pos_x' => 46,
                'pos_y' => 74,
                'latitude' => 50.259700,
                'longitude' => 4.912200,
                'sort_order' => 8,
            ],
            [
                'name' => 'Cite Ardente, Liege',
                'slug' => 'liege',
                'region' => 'Wallonie',
                'excerpt' => 'Entre Meuse et escaliers de la Montagne de Bueren.',
                'description' => 'Liege melange patrimoine industriel, art nouveau et une vie culturelle intense au bord de la Meuse.',
                'image_url' => 'https://images.unsplash.com/photo-1567939219730-b03d3f1c7a37?w=800&q=80',
                'cloudinary_public_id' => 'dog',
                'cloudinary_cloud_name' => 'demo',
                'pos_x' => 74,
                'pos_y' => 44,
                'latitude' => 50.632600,
                'longitude' => 5.579700,
                'sort_order' => 9,
            ],
        ];

        foreach ($landmarks as $landmark) {
            Landmark::updateOrCreate(['slug' => $landmark['slug']], $landmark);
        }
    }
}
