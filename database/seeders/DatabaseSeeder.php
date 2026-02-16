<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
// // Akun Admin
// User::factory()->create([
//     'name' => 'Administrator',
//     'email' => 'admin@example.com',
//     'password' => bcrypt('password'),
//     'role' => 'admin', // pastikan tabel users punya kolom role
// ]);

$adminExists = Pengguna::where('email', 'admin@bisindo.com')->first();

        if (!$adminExists) {
            Pengguna::create([
                'nama' => 'Administrator BISINDO',
                'email' => 'admin@bisindo.com',
                'kata_sandi' => Hash::make('admin123'),
                'peran' => 'admin',
                'email_terverifikasi_pada' => now(),
            ]);

            echo "✓ Akun admin berhasil dibuat!\n";
            echo "  Email: admin@bisindo.com\n";
            echo "  Password: admin123\n";
        } else {
            echo "✓ Akun admin sudah ada.\n";
        }

// Akun User Biasa
User::factory()->create([
    'name' => 'User Biasa',
    'email' => 'user@example.com',
    'password' => bcrypt('password'),
    'role' => 'user', // atau null jika tidak memakai role
]);

        $this->call([
            AbjadSeeder::class,
        ]);
    }
}
