<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password = null;

    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'nim' => fake()->unique()->numerify('##########'),
            'no_whatsapp' => '08' . fake()->numerify('##########'),
            'alamat' => fake()->address(),
            'kota' => 'Surabaya',
            'kode_pos' => fake()->numerify('#####'),
            'foto_ktm' => null,
            'role' => 'user',
            'status_verifikasi' => 'terverifikasi',
            'status_aktif' => true,
            'terakhir_online' => now(),
            'latitude' => null,
            'longitude' => null,
        ];
    }
}
