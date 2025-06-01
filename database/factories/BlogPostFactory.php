<?php

namespace Database\Factories;

use App\Models\BlogPost; // Додаємо для використання моделі BlogPost
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str; // Додаємо для Str::slug

class BlogPostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BlogPost::class; // Вказуємо, до якої моделі належить ця фабрика

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(rand(3, 8), true);
        $txt = $this->faker->realText(rand(1000, 4000)); // Генерируем более длинный текст
        $date = $this->faker->dateTimeBetween('-3 months', '-2 months');

        $createdAt = $date;
        $updatedAt = $date;
        $publishedAt = null;
        $isPublished = false;

        // Логика для is_published и published_at
        if (rand(1, 5) > 1) { // 80% шанс, что будет опубликовано
            $isPublished = true;
            $publishedAt = $date;
        }

        // Дополнительная логика для user_id и category_id
        // category_id должен быть из диапазона существующих id в blog_categories
        // user_id должен быть из диапазона существующих id в users

        // Учитывая, что "Без категории" имеет ID 1, а следующие идут с 2 по 11 (если у вас 10 + 1)
        $category_id = rand(1, 11); // Если у вас 11 категорий (1 без + 10 с циклом)

        // Учитывая, что у вас есть пользователь с ID 1 (Невідомий автор) и ID 2 (Автор)
        $user_id = (rand(1, 2) == 5) ? 1 : 2; // Це трохи дивна логіка. Якщо 5, то 1, інакше 2. Можливо, задум був: якщо 1, то 1, інакше 2?
        // Краще rand(1,2) для розподілу між двома авторами.
        // Змінимо:
        $user_id = rand(1, 2); // Випадковий вибір між ID 1 та ID 2

        return [
            'category_id'  => $category_id,
            'user_id'      => $user_id,
            'title'        => $title,
            'slug'         => Str::slug($title),
            'excerpt'      => $this->faker->text(rand(40, 100)),
            'content_raw'  => $txt,
            'content_html' => $txt, // Пока используем тот же текст для raw и html
            'is_published' => $isPublished,
            'published_at' => $publishedAt,
            'created_at'   => $createdAt,
            'updated_at'   => $updatedAt,
        ];
    }
}

