<?php

namespace Database\Seeders;

use App\Models\BlogPost; // Додаємо для використання фабрики BlogPost
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Додаємо, якщо ви також робили тут DB::table...
use Illuminate\Support\Str; // Додаємо, якщо ви також робили тут Str::random...
use App\Models\User; // Для Laravel 11, можливо, доведеться явно імпортувати User

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Якщо ви не використовуєте UsersTableSeeder для створення users,
        // ви можете розкоментувати наступний рядок, щоб створити декілька тестових користувачів.
        // User::factory(10)->create();

        $this->call(UsersTableSeeder::class);
        $this->call(BlogCategoriesTableSeeder::class);

        // Створюємо 100 записів блогів за допомогою фабрики BlogPost
        // Переконайтеся, що фабрика BlogPostFactory створена і налаштована
        BlogPost::factory(100)->create();
    }
}
