<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama' => 'Administrator',
            'email' => 'admin@campustrade.test',
            'password' => Hash::make('password'),
            'nim' => '0000000000',
            'no_whatsapp' => '081234567890',
            'alamat' => 'Surabaya',
            'kota' => 'Surabaya',
            'kode_pos' => '60100',
            'role' => 'admin',
            'status_verifikasi' => 'terverifikasi',
            'status_aktif' => true,
        ]);

        User::factory(5)->create();
    }
}
