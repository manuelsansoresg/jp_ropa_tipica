<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Section;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@jvropatipica.mx')],
            ['name' => 'Administrador JV', 'password' => Hash::make(env('ADMIN_PASSWORD', 'Cambiar123!'))],
        );

        foreach ([
            'brand_name' => 'JV Ropa Típica', 'whatsapp' => '5219999085831', 'phone_display' => '999 908 5831',
            'location' => 'Tekit, Yucatán, México', 'instagram_url' => '#', 'facebook_url' => '#',
        ] as $key => $value) {
            SiteSetting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        $categories = [
            ['name' => 'Guayaberas', 'slug' => 'guayaberas', 'description' => 'Lino, algodón y bordados que honran una silueta esencial de Yucatán.', 'image' => '/images/editorial/guayabera-patio.jpg', 'sort_order' => 1],
            ['name' => 'Vestidos', 'slug' => 'vestidos', 'description' => 'Prendas frescas y femeninas con detalles bordados de inspiración regional.', 'image' => '/images/editorial/vestido-bordado.jpg', 'sort_order' => 2],
            ['name' => 'Pantalones', 'slug' => 'pantalones', 'description' => 'Siluetas cómodas y sobrias para completar un guardarropa atemporal.', 'image' => '/images/editorial/pantalon-beige.jpg', 'sort_order' => 3],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::firstOrCreate(['slug' => $categoryData['slug']], $categoryData + ['active' => true]);
            $items = match ($category->slug) {
                'guayaberas' => [
                    ['Guayabera Blanca Clásica', 'guayabera-blanca-clasica', 'guayabera-clasica.jpg', 1450, 'Lino y algodón', ['Blanco', 'Marfil']],
                    ['Guayabera Bordada Punto de Cruz', 'guayabera-bordada-punto-cruz', 'bordado-detalle.jpg', 1850, 'Algodón bordado', ['Blanco']],
                    ['Guayabera Lino Tradicional', 'guayabera-lino-tradicional', 'guayabera-lino.jpg', 1690, 'Lino', ['Blanco', 'Marfil']],
                    ['Guayabera Manga Larga', 'guayabera-manga-larga', 'guayabera-negra.jpg', 1790, 'Lino y algodón', ['Negro', 'Blanco']],
                ],
                'vestidos' => [
                    ['Vestido Regional Blanco', 'vestido-regional-blanco', 'vestido-regional.jpg', 1950, 'Algodón bordado', ['Blanco']],
                    ['Vestido Bordado Tradicional', 'vestido-bordado-tradicional', 'vestido-bordado.jpg', 2190, 'Lino con bordado artesanal', ['Blanco', 'Marfil']],
                    ['Vestido Marfil Artesanal', 'vestido-marfil-artesanal', 'vestido-marfil.jpg', 2290, 'Algodón y encaje', ['Marfil']],
                ],
                default => [
                    ['Pantalón Negro Clásico', 'pantalon-negro-clasico', 'guayabera-patio.jpg', 980, 'Lino y algodón', ['Negro']],
                    ['Pantalón Beige Tradicional', 'pantalon-beige-tradicional', 'pantalon-beige.jpg', 980, 'Lino y algodón', ['Beige', 'Marfil']],
                ],
            };

            foreach ($items as [$name, $slug, $image, $price, $material, $colors]) {
                $product = Product::firstOrCreate(['slug' => $slug], [
                    'category_id' => $category->id, 'name' => $name,
                    'short_description' => 'Una pieza de líneas limpias, cómoda y cuidadosamente terminada en Yucatán.',
                    'description' => 'Diseñada para acompañar celebraciones y momentos cotidianos. Su construcción ligera, acabados precisos y carácter atemporal expresan la tradición yucateca desde una mirada contemporánea.',
                    'price' => $price, 'featured' => true, 'active' => true, 'material' => $material, 'colors' => $colors,
                ]);
                if ($product->wasRecentlyCreated) {
                    $product->images()->createMany([
                        ['image' => "/images/editorial/{$image}", 'sort_order' => 1],
                        ['image' => '/images/editorial/textura-lino.jpg', 'sort_order' => 2],
                    ]);
                    $product->sizes()->createMany(array_map(fn ($size) => ['name' => $size], ['CH', 'M', 'G', 'XG']));
                }
            }
        }

        Section::firstOrCreate(['key' => 'manifesto'], [
            'subtitle' => 'Desde Yucatán', 'title' => 'Vestimos una tradición que sigue evolucionando.',
            'content' => 'En JV Ropa Típica creemos que una prenda tradicional puede conservar su esencia y al mismo tiempo formar parte del estilo actual. Seleccionamos diseños que representan la elegancia y el carácter de Yucatán, cuidando cada detalle para ofrecer prendas cómodas, auténticas y listas para acompañarte en cualquier ocasión.',
            'image' => '/images/editorial/artesania.jpg', 'active' => true,
        ]);
        Section::firstOrCreate(['key' => 'about'], [
            'subtitle' => 'Nuestra historia', 'title' => 'Nuestra historia también se viste.',
            'content' => 'JV Ropa Típica nace en Yucatán con la intención de mantener vigente una forma de vestir que forma parte de nuestra identidad. Desde nuestros primeros modelos buscamos combinar tradición, comodidad y elegancia en prendas para ocasiones especiales y para la vida cotidiana. Hoy atendemos a clientes dentro y fuera del estado, enviando nuestras prendas a diferentes lugares de México.',
            'image' => '/images/editorial/patio-yucateco.jpg', 'active' => true,
        ]);

        foreach ([
            ['Carlos M.', 'Muy bonita la guayabera y la talla quedó perfecta. Me ayudaron por WhatsApp antes de hacer el pedido.'],
            ['Andrea R.', 'Pedí un vestido para un evento y llegó muy bien empacado. Excelente atención.'],
            ['Laura P.', 'El diseño está precioso y se nota mucho la calidad del bordado.'],
        ] as [$name, $text]) {
            Testimonial::firstOrCreate(['name' => $name], ['text' => $text, 'active' => true, 'is_demo' => true]);
        }
    }
}
