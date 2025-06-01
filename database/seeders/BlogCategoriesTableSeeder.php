<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str; // Додаємо для Str::slug
use Illuminate\Support\Facades\DB; // Додаємо для DB::table

class BlogCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [];

        $cName = 'Без категорії';
        $categories[] = [
            'title'     => $cName,
            'slug'      => Str::slug($cName),
            'parent_id' => 0, // Змінив на 0, оскільки 1 вже може бути зайнято
        ];

        for ($i = 1; $i <=10; $i++) {
            $cName = 'Категорія #' . $i;
            $parentId = ($i > 4) ? rand(1, 4) : 1; // Тут варто врахувати, що parent_id=0 не може бути, тому 1 - це "Без категорії"

            $categories[] = [
                'title'     => $cName,
                'slug'      => Str::slug($cName),
                'parent_id' => $parentId,
            ];
        }

        DB::table('blog_categories')->insert($categories);
    }
}
