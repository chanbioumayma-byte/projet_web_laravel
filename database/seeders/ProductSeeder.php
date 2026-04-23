<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $users      = User::all();
        $categories = Category::all();

        $products = [
            [
                'title'       => 'Laptop Pro 15"',
                'description' => 'Ordinateur portable haute performance, Intel Core i7, 16 Go RAM, 512 Go SSD. Idéal pour les développeurs et créatifs.',
                'price'       => 1299.99,
                'category'    => 'Électronique',
            ],
            [
                'title'       => 'Smartphone Galaxy X',
                'description' => 'Écran AMOLED 6.5", appareil photo 108 MP, batterie 5000 mAh, charge rapide 65W.',
                'price'       => 599.99,
                'category'    => 'Électronique',
            ],
            [
                'title'       => 'Écouteurs Bluetooth',
                'description' => 'Réduction de bruit active, autonomie 30h, son cristallin. Compatible iOS et Android.',
                'price'       => 89.99,
                'category'    => 'Électronique',
            ],
            [
                'title'       => 'T-Shirt Premium Coton',
                'description' => 'T-shirt 100% coton bio, coupe moderne, disponible en plusieurs couleurs.',
                'price'       => 24.99,
                'category'    => 'Vêtements',
            ],
            [
                'title'       => 'Veste Cuir Homme',
                'description' => 'Veste en cuir véritable, doublure chaude, poches multiples. Style urbain intemporel.',
                'price'       => 189.99,
                'category'    => 'Vêtements',
            ],
            [
                'title'       => 'Laravel: Up & Running',
                'description' => 'Le guide de référence complet pour maîtriser le framework Laravel, de l\'installation aux concepts avancés.',
                'price'       => 39.99,
                'category'    => 'Livres',
            ],
            [
                'title'       => 'Clean Code',
                'description' => 'Le livre incontournable de Robert C. Martin sur l\'écriture de code propre et maintenable.',
                'price'       => 34.99,
                'category'    => 'Livres',
            ],
            [
                'title'       => 'Cafetière Expresso',
                'description' => 'Machine à café professionnelle, 15 bars de pression, mousse le lait automatiquement.',
                'price'       => 149.99,
                'category'    => 'Maison & Jardin',
            ],
            [
                'title'       => 'Tapis de Yoga',
                'description' => 'Tapis antidérapant 6mm d\'épaisseur, matériau écologique TPE, dimensions 183x61cm.',
                'price'       => 45.99,
                'category'    => 'Sports',
            ],
            [
                'title'       => 'Vélo de Route Carbon',
                'description' => 'Cadre carbone ultraléger, 22 vitesses Shimano, freins à disque hydrauliques.',
                'price'       => 1899.99,
                'category'    => 'Sports',
            ],
            [
                'title'       => 'Huile d\'Argan Bio',
                'description' => 'Huile d\'argan 100% pure et naturelle, certifiée bio, idéale pour cheveux et peau.',
                'price'       => 19.99,
                'category'    => 'Beauté & Santé',
            ],
            [
                'title'       => 'Thé Vert Matcha Bio',
                'description' => 'Matcha cérémoniel de qualité supérieure, riche en antioxydants, origine Japon.',
                'price'       => 29.99,
                'category'    => 'Alimentation',
            ],
        ];

        foreach ($products as $p) {
            $cat = $categories->where('name', $p['category'])->first();
            Product::create([
                'user_id'     => $users->random()->id,
                'category_id' => $cat ? $cat->id : $categories->first()->id,
                'title'       => $p['title'],
                'description' => $p['description'],
                'price'       => $p['price'],
            ]);
        }
    }
}
