<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str; // Додаємо для Str::random
use Illuminate\Support\Facades\DB; // Додаємо для DB::table
use Illuminate\Support\Facades\Hash; // Додаємо для Hash::make, якщо використовуєте його

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name'      => 'Невідомий автор',
                'email'     => 'author_unknown@g.g',
                'password'  => Hash::make(Str::random(16)), // Використовуємо Hash::make
                'email_verified_at' => now(), // Додаємо, якщо використовуєте Jetstream, інакше можуть бути проблеми з логіном
            ],
            [
                'name'      => 'Автор',
                'email'     => 'author1@g.g',
                'password'  => Hash::make('123123'), // Використовуємо Hash::make('123123')
                'email_verified_at' => now(), // Додаємо
            ],
        ];
        DB::table('users')->insert($data);
    }
}
