<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1 Akun Admin (password di-hash secara manual)
        User::updateOrCreate(
            ['email' => 'admin@SweetLili.test'],
            [
                'name' => 'Admin SweetLili',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
