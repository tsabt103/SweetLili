<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
    [
        'name' => 'Kue Basah',
        'description' => 'Kue tradisional dan kue basah fresh dibuat tiap hari.',
    ],
    [
        'name' => 'Kue Kering',
        'description' => 'Cookies dan kue kering renyah untuk stok di rumah.',
    ],
    [
        'name' => 'Roti & Pastry',
        'description' => 'Roti empuk dan pastry panggang fresh tiap pagi.',
    ],
    [
        'name' => 'Dessert & Pudding',
        'description' => 'Dessert lembut dan pudding dingin yang manisnya pas.',
    ],
];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['name' => $cat['name']],
                [
                    'slug' => Str::slug($cat['name']),
                    'description' => $cat['description'],
                ]
            );
        }
    }
}
