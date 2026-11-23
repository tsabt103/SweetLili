<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryMap = Category::pluck('id', 'name')->toArray();
$products = [
    [
        'name' => 'Brownies Lumer SweetLili',
        'category_name' => 'Kue Basah',
        'price' => 25000,
        'stock' => 20,
        'status' => 'active',
        'description' => 'Browwnies coklat panggang lumer di tengah, topping choco chips, best seller SweetLili.',
    ],
    [
        'name' => 'Dubai chewy cookie',
        'category_name' => 'Dessert & Pudding',
        'price' => 25000,
        'stock' => 20,
        'status' => 'active',
        'description' => 'Cookie tebal viral ala Dubai, isi krim pistachio gurih, kunafah renyah, dan lelehan coklat lumer di tengahnya.',
    ],
    [
        'name' => 'Donat Glaze Coklat',
        'category_name' => 'Roti & Pastry',
        'price' => 12000,
        'stock' => 35,
        'status' => 'active',
        'description' => 'Donat empuk goreng fresh tiap pagi dengan glaze coklat tebal dan meses warna-warni.',
    ],
    [
        'name' => 'Cookies Chocochip',
        'category_name' => 'Kue Kering',
        'price' => 15000,
        'stock' => 50,
        'status' => 'active',
        'description' => 'Cookies renyah di luar lembut di dalam, isian choco chips melimpah.',
    ],
    [
        'name' => 'Klepon Gula Merah',
        'category_name' => 'Kue Basah',
        'price' => 10000,
        'stock' => 40,
        'status' => 'active',
        'description' => 'Klepon kenyal isi gula merah lumer, taburan kelapa parut gurih.',
    ],
    [
        'name' => 'Cheesecake Lumer',
        'category_name' => 'Dessert & Pudding',
        'price' => 35000,
        'stock' => 12,
        'status' => 'active',
        'description' => 'Cheesecake creamy lembut dengan topping keju parut, lumer di mulut.',
    ],
    [
        'name' => 'Roti Sobek Coklat',
        'category_name' => 'Roti & Pastry',
        'price' => 20000,
        'stock' => 25,
        'status' => 'active',
        'description' => 'Roti sobek super empuk isi coklat lumer, cocok buat temen ngeteh.',
    ],
    [
        'name' => 'Cupcake Red Velvet',
        'category_name' => 'Kue Basah',
        'price' => 18000,
        'stock' => 18,
        'status' => 'active',
        'description' => 'Cupcake red velvet lembut dengan krim keju manis gurih di atasnya.',
    ],
    [
        'name' => 'Bolu Pandan',
        'category_name' => 'Kue Basah',
        'price' => 22000,
        'stock' => 15,
        'status' => 'active',
        'description' => 'Bolu pandan wangi lembut, pakai pandan asli bukan pewarna.',
    ],
    [
        'name' => 'Pudding Coklat Susu',
        'category_name' => 'Dessert & Pudding',
        'price' => 14000,
        'stock' => 30,
        'status' => 'active',
        'description' => 'Pudding coklat susu super lembut, disajikan dingin lebih nikmat.',
    ],
    [
        'name' => 'Nastar Nanas',
        'category_name' => 'Kue Kering',
        'price' => 28000,
        'stock' => 8,
        'status' => 'active',
        'description' => 'Nastar lumer isi selai nanas homemade, stok terbatas tiap batch.',
    ],
];

        foreach ($products as $item) {
            Product::create([
                'name' => $item['name'],
                'category_id' => $categoryMap[$item['category_name']] ?? null,
                'category' => $item['category_name'],
                'price' => $item['price'],
                'stock' => $item['stock'],
                'status' => $item['status'],
                'description' => $item['description'],
            ]);
        }
    }
}
