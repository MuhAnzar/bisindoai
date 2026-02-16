<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah admin sudah ada
        $adminExists = Pengguna::where('email', 'admin@bisindo.com')->first();

        if (!$adminExists) {
            Pengguna::create([
                'nama' => 'Administrator BISINDO',
                'email' => 'admin@bisindo.com',
                'kata_sandi' => 'admin123',
                'peran' => 'admin',
                'email_terverifikasi_pada' => now(),
            ]);

            echo "✓ Akun admin berhasil dibuat!\n";
            echo "  Email: admin@bisindo.com\n";
            echo "  Password: admin123\n";
        } else {
            echo "✓ Akun admin sudah ada.\n";
        }
    }
}
